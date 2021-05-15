<?php

namespace App\Controller;

use App\Entity\CartDish;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    private $em;
    private $session;
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->em = $entityManager;
        $this->session = $session;
    }
    /**
     * @Route("/", name="cart")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/add/dish" , name="add_dish_to_cart" , options={"expose"=true} )
     * @throws \Exception
     */
    public function addDishToCart(Request $request, CartDishRepository $cartDishRepository, DishRepository $dishRepository)
    {
        $dish = $request->query->get('id_dish');
        if (null !== $dish && $dish > 0) {
            $dish = $dishRepository->find($dish);
        } else {
            throw new NotFoundHttpException('i cant find this dish');
        }

        $user = $this->getUser();
        if (null === $user) {
            return new JsonResponse(['error' => false, 'msg'=> 'user not authorized', 'code' => 403 ]);
        }

        if (!$this->session->has('_cart')) {
            $cart = new CartDish();
            $tokenIdSession = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            $cart->setSessionId($tokenIdSession);
            $this->session->set('_cart', $tokenIdSession);
        } else {
            $tokenIdSession = $this->session->get('_cart');
            $cart = $cartDishRepository
                ->findOneBy(['sessionId' => $tokenIdSession]);
        }

        $cart->addDish($dish);
        $quantity = $cart->getQuanty() + 1;
        $cart->setQuanty($quantity);
        $cart->setDto(0); // TODO SET RESTJAV-008 HERE FOR PROMOTIONS

        $this->em->persist($cart);
        $this->em->flush();

        return new JsonResponse(['error' => false, 'msg'=> 'success', 'code' => 200 ]);
    }
}
