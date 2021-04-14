<?php

namespace App\EventSubscriber;

use App\Events\UserRegistrationEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $session;
    private $logger;


    public function __construct(MailerInterface $mailer, SessionInterface $session, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->session = $session;
        $this->logger = $logger;
    }


    public function onUserChangePassword(UserRegistrationEvent $event)
    {
        // TODO
    }

    public function onUserRegistration(UserRegistrationEvent $event)
    {
        $this->logger->info('onUserRegistration entered');
        $email = (new TemplatedEmail())
            ->from('admin@restajav.com')
            ->to($event->getUser()->getEmail())
            ->subject('Your account in RestaJav has been created')
            ->htmlTemplate('emails/user/registration/signup.html.twig')
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

    public function onUserConfirmedEmail(UserRegistrationEvent $event)
    {
        $this->logger->info('onUserConfirmedEmail entered');
        $email = (new TemplatedEmail())
            ->from('admin@restajav.com')
            ->to($event->getUser()->getEmail())
            ->subject('Email Account confirmed!')
            ->htmlTemplate('emails/user/registration/confirmed.html.twig')
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
            UserRegistrationEvent::USER_NEW_SIGNUP => 'onUserRegistration',
            UserRegistrationEvent::CHANGE_PASSWORD => 'onUserChangePassword',
            UserRegistrationEvent::CONFIRMED_EMAIL => 'onUserConfirmedEmail'
        ];
    }
}
