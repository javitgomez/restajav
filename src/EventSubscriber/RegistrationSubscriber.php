<?php

namespace App\EventSubscriber;

use App\Events\UserRegistrationEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

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

    public function onUserRegistration(UserRegistrationEvent $event)
    {
        $this->logger->info('onUserRegistration entered');
        $email = (new TemplatedEmail())
            ->from(new Address('registro@horuslegalalliance.es', 'RestaJav'))
            ->to($event->getUser()->getEmail())
            ->subject('Su cuenta de usuario ha sido creada')
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
            ->from(new Address('registro@horuslegalalliance.es', 'RestaJav'))
            ->to($event->getUser()->getEmail())
            ->subject('Su email de usuario ha sido confirmado')
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

    public function onUserResetPassword(UserRegistrationEvent $event)
    {
        $this->logger->info('onUserResetPassword entered');
        $email = (new TemplatedEmail())
            ->from(new Address('registro@horuslegalalliance.es', 'RestaJav'))
            ->to($event->getUser()->getEmail())
            ->subject('Solicitud de cambio de contraseña')
            ->htmlTemplate('emails/user/reset/linkAccess.html.twig')
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
            UserRegistrationEvent::CONFIRMED_EMAIL => 'onUserConfirmedEmail',
            UserRegistrationEvent::RESET_PASS_MAIL => 'onUserResetPassword'
        ];
    }
}
