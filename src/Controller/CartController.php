<?php

namespace App\Controller;

use App\Entity\CartDish;
use App\Entity\Dish;
use App\Entity\Link;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Promotion;
use App\Events\OrderEvent;
use App\Form\CategoryType;
use App\Form\PaymentType;
use App\Form\AddressType;
use App\Repository\CartDishRepository;
use App\Repository\DishRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\CustomManagerRepository;

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
        $dtoToApply = $this->findDtoToApply($dish);
        $cart->setDto($dtoToApply['dto'] ?? 0);

        $this->em->persist($cart);
        $this->em->flush();
        $quantity = $cartDishRepository->count(['sessionId' => $tokenIdSession]);

        return new JsonResponse(['error' => false, 'msg'=> 'success', 'code' => 200 , 'data'  => ['quantity' => $quantity] ]);
    }

    /**
     * @param Dish $dish
     * @return int
     */
    private function findDtoToApply(Dish $dish) : array
    {
        $promotions = $this->em
            ->getRepository(Promotion::class)
            ->findActivePromotion($dish);

        if (!empty($promotions)) {
            return array_pop($promotions);
        }
        return [];
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
    public function cart(CartDishRepository $cartDishRepository, CustomManagerRepository $customManagerRepository): Response
    {
        $tokenIdSession = $this->session->get('_cart');
        $cart = $cartDishRepository
            ->findBy(['sessionId' => $tokenIdSession]);
        $links['header'] = $this->prepareLinksHeader();
        return $this->render(
            'cart/index.html.twig',
            [
                'cart' => $cart,
                'links' => $links,
                'customManager' => $customManagerRepository->find(1)
            ]
        );
    }

    private function prepareLinksHeader() : array
    {
        $links = $this->em->getRepository(Link::class)->findAll();
        $menu = [];
        $only = ['home','about','menu','especiales','eventos','chef','galeria','cesta','contacto'];

        if (!empty($links)) {
            foreach ($links as $link) {
                if (in_array($link->getSlug(), $only)) {
                    $menu[$link->getId()] = ['name' => $link->getName(), 'href' => $link->getHref()];
                }
            }
        } else {
            throw new Exception('this menu should be filled before');
        }

        return $menu;
    }

    /**
     * @Route("/ok/{id}" , name="order_confirm_ok")
     * @ParamConverter("order", class="App\Entity\Order")
     * @throws \Exception
     */
    public function order_ok(EventDispatcherInterface $dispatcher, Order $order, CustomManagerRepository $customManagerRepository): Response
    {
        $tokenIdSession = $this->session->get('_cart');

        if (!$tokenIdSession) {
            throw new UnauthorizedHttpException('you cant allow here');
        }

        $this->session->remove('_cart');

        $orderCreatedEvent = new OrderEvent($this->getUser(), $order);
        $dispatcher->dispatch($orderCreatedEvent, $orderCreatedEvent::ORDER_CREATED);

        $this->addFlash('success_order', 'El pedido se ha realizado correctamente.');

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/ko/" , name="order_confirm_ko")
     * @throws \Exception
     */
    public function order_ko(CustomManagerRepository $customManagerRepository): Response
    {
        $tokenIdSession = $this->session->get('_cart');
        if (!$tokenIdSession) {
            throw new UnauthorizedHttpException('you cant allow here');
        }

        $this->session->remove('_cart');

        $this->addFlash('fail_order', 'Hubo un problema al realizar el pedido.');

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/check_address/{id}" , name="check_address")
     * @ParamConverter("order", class="App\Entity\Order")
     *
     * @throws \Exception
     */
    public function checkAddress(Request $request, Order $order, CustomManagerRepository $customManagerRepository)
    {
        $address = $this->getUser()->getAddress() ?? new \App\Entity\Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $address->setUser($this->getUser());
                $this->em->persist($address);
                $this->em->flush();
                return $this->redirectToRoute('order_confirm_ok', ['id' => $order->getId()]);
            } catch (Exception $e) {
                $this->addFlash('fail_order', $e->getMessage());
                return $this->redirectToRoute('order_confirm_ko');
            }
        }
        $links['header'] = $this->prepareLinksHeader();
        return $this->render('order/address.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
            'links' => $links,
            'customManager' => $customManagerRepository->find(1)
        ]);
    }

    /**
     * @Route("/payment_method/{id}" , name="payment_method")
     * @ParamConverter("order", class="App\Entity\Order")
     *
     * @throws \Exception
     */
    public function paymentMethod(Request $request, Order $order, CustomManagerRepository $customManagerRepository): Response
    {
        $form = $this->createForm(PaymentType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($order);
                $this->em->flush();
                return $this->redirectToRoute('check_address', ['id'=>$order->getId()]);
            } catch (Exception $e) {
                $this->addFlash('fail_order', $e->getMessage());
                return $this->redirectToRoute('order_confirm_ko');
            }
        }
        
        $links['header'] = $this->prepareLinksHeader();

        return $this->render('order/payment_method.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
            'links' => $links,
            'customManager' => $customManagerRepository->find(1)
        ]);
    }

    /**
     * @Route("/create/" , name="order_create" , methods={"POST"} )
     * @throws \Exception
     */
    public function createOrder(Request $request, CartDishRepository $cartDishRepository, DishRepository $dishRepository, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $tokenIdSession = $this->session->get('_cart');
        $codePromotion = $request->request->get('code-promotion');
        $cart = $cartDishRepository
            ->findBy(['sessionId' => $tokenIdSession]);
        if (!$cart) {
            throw new \Exception('i cant do the order. try later');
        }

        $order = new Order();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $order->setState(Order::RECEIVED);
        $sumTotalOrder = 0.0;
        $sumTotaDto = 0.0;

        foreach ($cart as $item) {
            /** @var \App\Entity\CartDish $item */
            $orderItem = new OrderItem();
            $orderItem->setQuanty($item->getQuanty());

            $dish = $dishRepository->find($item->getDishId());
            if (!$dish) {
                throw new Exception('the dish has been deleted from server');
            }

            $prizeWithDtoApplied = $this->applyDto($dish, $codePromotion);
            if (null !== $prizeWithDtoApplied) {
                $sumTotaDto += $dish->getPrize() - $prizeWithDtoApplied;
            }

            $sumTotalOrder += $dish->getPrize() ;
           
            $orderItem->setDish($dish);
            $this->em->remove($item);
            $order->addItem($orderItem);
        }

        $order->setUser($user);
        $order->setTotal($sumTotalOrder);
        $order->setTotalDto($sumTotaDto);

        $this->em->persist($order);
        $this->em->flush();

        return $this->redirectToRoute('payment_method', ['id' => $order->getId()]);
    }

    public function applyDto(Dish $dish, string $codePromotion)
    {
        $promotionsRepository = $this->em->getRepository(Promotion::class);

        $dtoToApply = $promotionsRepository->findDtoToApply($dish, $codePromotion);

        return (null !== $dtoToApply) ? $dish->getPrize() - ($dish->getPrize() * $dtoToApply / 100) : null;
    }
}
