<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Events\OrderCreatedEvent;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orion/order")
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
     * @Route("/show/" , name="admin_order_review")
     * @throws \Exception
     */
    public function show(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/view/{orderId}" , name="admin_order_show")
     * @throws \Exception
     */
    public function view(OrderRepository $orderRepository, $orderId): Response
    {
        $order = $orderRepository->find($orderId);
        if (!$order) {
            throw new NotFoundHttpException();
        }

        return $this->render('order/show.html.twig', [
            'order' => $order
        ]);
    }
}
