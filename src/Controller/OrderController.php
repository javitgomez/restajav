<?php

namespace App\Controller;

use App\Entity\CartDish;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    private $em;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->em = $entityManager;
        $this->session = $session;
    }

    /**
     * @Route("/ok/" , name="order_confirm_ok")
     * @throws \Exception
     */
    public function order_ok()
    {
        return $this->render('order/ok.html.twig', []);
    }

    /**
     * @Route("/ko/" , name="order_confirm_ko")
     * @throws \Exception
     */
    public function order_ko()
    {
    }
    /**
     * @Route("/create/" , name="order_create")
     * @throws \Exception
     */
    public function createOrder(CartDishRepository $cartDishRepository, DishRepository $dishRepository): RedirectResponse
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
            $order->addItem($orderItem);
        }
        $order->setUser($user);
        $order->setTotal($sumTotalOrder);
        $order->setTotalDto(0.00);


        $this->em->persist($order);
        $this->em->flush();


        $this->addFlash('info', 'el pedido se ha realizado correctamente. \\
        Recibirá un correo con la confirmación del pedido');

        // TODO remove token_id_sesion
        // TODO remove _cart session variable
        // TODO send email to client for confirmation (event for example)

        return $this->redirectToRoute('order_confirm_ok');
    }
}
