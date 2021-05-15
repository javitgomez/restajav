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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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
        $session = new Session();

        if (!$session->has('cart_')) {
            $cart = new CartDish();
            $tokenIdSession = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            $cart->setSessionId($tokenIdSession);
            $session->set('_cart', $tokenIdSession);
        } else {
            $cart = $cartDishRepository
                ->findOneBy(['sessionId' => $session->get('cart_')]);
        }

        $cart->addDish($dish);
        $quantity = $cart->getQuanty() + 1;
        $cart->setQuanty($quantity);
        $cart->setDto(0); // TODO SET RESTJAV-008 HERE FOR PROMOTIONS

        $this->em->persist($cart);
        $this->em->flush();
        $jsonResponse = ['error' => false, 'msg'=> 'success', 'code' => 200 ];
        return new JsonResponse($jsonResponse);
    }
}
