<?php

namespace App\EventSubscriber;

use App\Events\OrderEvent;
use App\Events\UserRegistrationEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;

class OrderSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $logger;


    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function onOrderCreated(OrderEvent $event)
    {
        $this->logger->info('on Order created');
        $email = (new TemplatedEmail())
            ->from('admin@restajav.com')
            ->to($event->getUser()->getEmail())
            ->subject('Your Order has been realized')
            ->htmlTemplate('emails/order/created.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'user' => $event->getUser(),
                'order' => $event->getOrder(),
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function onOrderDelivered(OrderEvent $event)
    {
        $this->logger->info('on Order created');
        $email = (new TemplatedEmail())
            ->from('admin@restajav.com')
            ->to($event->getUser()->getEmail())
            ->subject('¡Tu ha pedido ha sido entregado!')
            ->htmlTemplate('emails/survey/index.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'user' => $event->getUser(),
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvent::ORDER_CREATED   => 'onOrderCreated',
            OrderEvent::ORDER_DELIVERED => 'onOrderDelivered',
        ];
    }
}
