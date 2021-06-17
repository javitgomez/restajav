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
use Symfony\Component\Mime\Address;
use App\Repository\CustomManagerRepository;

class OrderSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $logger;
    private $customManagerRepository;


    public function __construct(MailerInterface $mailer, LoggerInterface $logger, CustomManagerRepository $customManagerRepository)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->customManagerRepository = $customManagerRepository;
        
    }

    public function onOrderCreated(OrderEvent $event)
    {
        $this->logger->info('on Order created');
        $email = (new TemplatedEmail())
            ->from(new Address('registro@horuslegalalliance.es', 'RestaJav'))
            ->to($event->getUser()->getEmail())
            ->subject('Su pedido ha sido realizado')
            ->htmlTemplate('emails/order/created.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'user' => $event->getUser(),
                'order' => $event->getOrder(),
                'customManager' =>$this->customManagerRepository->find(1),
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
            OrderEvent::ORDER_CREATED   => 'onOrderCreated'
        ];
    }
}
