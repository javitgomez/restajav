<?php

namespace App\Controller;

use App\Entity\CartDish;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            $tokenIdSession = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            $this->session->set('_cart', $tokenIdSession);
        } else {
            $tokenIdSession = $this->session->get('_cart');
        }

        $cart = $cartDishRepository
            ->findOneBy(['sessionId' => $tokenIdSession, 'dishId' => $dish->getId()]);
        if (!$cart) {
            $cart = new CartDish();
        }

        $cart->setSessionId($tokenIdSession);

        $quantity = count($cartDishRepository->findBy(['sessionId' => $tokenIdSession]));

        $cart->setDishId($dish->getId());
        $cart->setQuanty($cart->getQuanty() + 1);
        $cart->setDto(0); // TODO SET RESTJAV-008 HERE FOR PROMOTIONS

        $this->em->persist($cart);
        $this->em->flush();

        return new JsonResponse(['error' => false, 'msg'=> 'success', 'code' => 200 , 'data'  => ['quantity' => $quantity] ]);
    }

    /**
     * @Route("/del/dish/{dishId}" , name="del_dish_to_cart" )
     * @throws \Exception
     */
    public function deleteDishToCart(CartDishRepository $cartDishRepository, $dishId): RedirectResponse
    {
        $tokenIdSession = $this->session->get('_cart');

        $dishOrder = $cartDishRepository
            ->findOneBy(['sessionId' => $tokenIdSession, 'dishId' => $dishId]);
        if (!$dishOrder) {
            throw new Exception('simon says: you cant not enter here');
        }
        $dishOrder->setQuanty($dishOrder->getQuanty() - 1);
        $this->em->remove($dishOrder);
        $this->em->flush();

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/update/dish/{dishId}/{quanty}" , name="update_dish_to_cart" , options={"expose"=true} )
     * @throws \Exception
     */
    public function updateDishToCart(CartDishRepository $cartDishRepository, $dishId, $quanty): RedirectResponse
    {
        $tokenIdSession = $this->session->get('_cart');

        $dishOrder = $cartDishRepository
            ->findOneBy(['sessionId' => $tokenIdSession, 'dishId' => $dishId]);
        if (!$dishOrder) {
            throw new Exception('simon says: you cant not enter here');
        }
        $dishOrder->setQuanty($quanty);
        $this->em->persist($dishOrder);
        $this->em->flush();

        return $this->redirectToRoute('cart');
    }


    /**
    * @Route("/" , name="cart" , options={"expose"=true} )
    * @throws \Exception
    */
    public function cart(CartDishRepository $cartDishRepository): Response
    {
        $tokenIdSession = $this->session->get('_cart');
        $cart = $cartDishRepository
            ->findBy(['sessionId' => $tokenIdSession]);

        return $this->render(
            'cart/index.html.twig',
            [
                'cart' => $cart
            ]
        );
    }
}
