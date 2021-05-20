<?php

namespace App\Controller;

use App\Entity\CartDish;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Events\OrderCreatedEvent;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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



        $cart->setDishId($dish->getId());
        $cart->setQuanty($cart->getQuanty() + 1);
        $cart->setDto(0); // TODO SET RESTJAV-008 HERE FOR PROMOTIONS

        $this->em->persist($cart);
        $this->em->flush();
        $quantity = $cartDishRepository->count(['sessionId' => $tokenIdSession]);

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

    /**
     * @Route("/ok/" , name="order_confirm_ok")
     * @throws \Exception
     */
    public function order_ok(): Response
    {
        $tokenIdSession = $this->session->get('_cart');

        if (!$tokenIdSession) {
            throw new UnauthorizedHttpException('you cant allow here');
        }

        $message = 'El pedido se ha realizado correctamente. Recibirá un correo con la confirmación del pedido.';
        $this->addFlash('success_order', $message);
        $this->session->remove('_cart');

        return $this->render('order/ok.html.twig', []);
    }

    /**
     * @Route("/ko/" , name="order_confirm_ko")
     * @throws \Exception
     */
    public function order_ko(): Response
    {
        $tokenIdSession = $this->session->get('_cart');
        if (!$tokenIdSession) {
            throw new UnauthorizedHttpException('you cant allow here');
        }

        $this->session->remove('_cart');

        return $this->render('order/ko.html.twig', []);
    }
    /**
     * @Route("/create/" , name="order_create")
     * @throws \Exception
     */
    public function createOrder(CartDishRepository $cartDishRepository, DishRepository $dishRepository, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $tokenIdSession = $this->session->get('_cart');

        $cart = $cartDishRepository
            ->findBy(['sessionId' => $tokenIdSession]);
        if (!$cart) {
            throw new Exception('i cant do the order. try later');
        }

        $order = new Order();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $order->setState(Order::RECEIVED);
        $sumTotalOrder = 0.0;

        foreach ($cart as $item) {
            /** @var \App\Entity\CartDish $item */
            $orderItem = new OrderItem();
            $orderItem->setQuanty($item->getQuanty());

            $dish = $dishRepository->find($item->getDishId());
            if (!$dish) {
                throw new Exception('the dish has been deleted from server');
            }

            $sumTotalOrder += $dish->getPrize();
            // TODO set dto here
            $orderItem->setDish($dish);
            $this->em->remove($item);
            $order->addItem($orderItem);
        }

        $order->setUser($user);
        $order->setTotal($sumTotalOrder);
        $order->setTotalDto(0.00);

        try {
            $this->em->persist($order);
            $this->em->flush();
        } catch (Exception $e) {
            $this->addFlash('fail_order', $e->getMessage());
            return $this->redirectToRoute('order_confirm_ko');
        }

        $orderCreatedEvent = new OrderCreatedEvent($user, $order);
        $dispatcher->dispatch($orderCreatedEvent, $orderCreatedEvent::ORDER_NEW_CREATED);

        return $this->redirectToRoute('order_confirm_ok');
    }
}
