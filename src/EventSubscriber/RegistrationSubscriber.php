<?php

namespace App\EventSubscriber;

use App\Events\UserRegistrationEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $session;
    private $logger;

    public function __construct(MailerInterface $mailer, SessionInterface $session,LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->session = $session;
        $this->logger = $logger;
    }


    public function onUserChangePassword(UserRegistrationEvent $event)
    {
        // TODO
        $test = 'this ok';
        $event_t = $event;
    }

    public function onUserRegistration(UserRegistrationEvent $event)
    {
        // TODO
        $test = 'this ok 2';
        $event_t = $event;

        $email = (new Email())
            ->from('hello@restajav.com')
            ->to($event->getUser()->getEmail())
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegistrationEvent::USER_NEW => 'onUserRegistration',
            UserRegistrationEvent::CHANGE_PASSWORD => 'onUserChangePassword'
        ];
    }
}