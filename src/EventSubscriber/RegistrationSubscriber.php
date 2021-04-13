<?php

namespace App\EventSubscriber;

use App\Events\UserRegistrationEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $session;

    public function __construct(MailerInterface $mailer, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->session = $session;
    }


    public function onUserChangePassword(UserRegistrationEvent $event)
    {
        // TODO
    }

    public function onUserRegistration(UserRegistrationEvent $event)
    {
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

    public static function getSubscribedEvents()
    {
        return [
            UserRegistrationEvent::USER_NEW => 'onUserRegistration',
            UserRegistrationEvent::CHANGE_PASSWORD => 'onUserChangePassword'
        ];
    }
}
