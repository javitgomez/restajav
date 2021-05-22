<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Events\OrderCreatedEvent;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use App\Repository\OrderRepository;
use App\Services\OrderManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("orion/order")
 */
class OrderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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

    /**
     * @Route("/workflow_canceled/{id}", name="order_workflow_canceled")
     * @ParamConverter("order", class="App\Entity\Order")
     */
    public function workflowCanceled(OrderManager $orderManager, Order $order): RedirectResponse
    {
        $orderManager->cancel($order);
        // TODO EVENT HERE FOR CANCEL BOOK
        // SEND EMAIL TO CLIENT
        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('admin_order_review');
    }

    /**
     * @Route("/workflow_pending/{id}", name="order_workflow_pending")
     * @ParamConverter("order", class="App\Entity\Order")
     */
    public function workflowPending(OrderManager $orderManager, Order $order): RedirectResponse
    {
        $orderManager->pending($order);
        // TODO EVENT HERE FOR CANCEL BOOK
        // SEND EMAIL TO CLIENT
        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('admin_order_review');
    }

    /**
     * @Route("/workflow_on_travel/{id}", name="order_workflow_on_travel")
     * @ParamConverter("order", class="App\Entity\Order")
     */
    public function workflowOnTravel(OrderManager $orderManager, Order $order): RedirectResponse
    {
        $orderManager->on_travel($order);
        // TODO EVENT HERE FOR CANCEL BOOK
        // SEND EMAIL TO CLIENT
        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('admin_order_review');
    }

    /**
     * @Route("/workflow_delivered/{id}", name="order_workflow_delivered")
     * @ParamConverter("order", class="App\Entity\Order")
     */
    public function workflowDelivered(OrderManager $orderManager, Order $order): RedirectResponse
    {
        $orderManager->delivered($order);
        // TODO EVENT HERE FOR CANCEL BOOK
        // SEND EMAIL TO CLIENT
        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('admin_order_review');
    }
}
