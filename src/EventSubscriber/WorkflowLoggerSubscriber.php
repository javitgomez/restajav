<?php
// src/App/EventSubscriber/WorkflowLoggerSubscriber.php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Events\UserRegistrationEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\Workflow\Event\Event;

class WorkflowLoggerSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $mailer;

    public function __construct(LoggerInterface $logger,MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function onLeave(Event $event)
    {
        $this->logger->alert(sprintf(
            'Workflow %s (id: "%s") performed transition "%s" from "%s" to "%s"',
            $event->getWorkflowName(),
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()->getTos())
        ));

        // TODO send something here
    }

    public function onOrderCompletedTransition(Event $event)
    {
        if($event->getTransition()->getName() === 'mark_as_on_travel') {
            $this->logger->alert(sprintf(
                'Workflow %s (id: "%s") performed transition "%s" from "%s" to "%s"',
                $event->getWorkflowName(),
                $event->getSubject()->getId(),
                $event->getTransition()->getName(),
                implode(', ', array_keys($event->getMarking()->getPlaces())),
                implode(', ', $event->getTransition()->getTos())
            ));

            $this->sendMail($event, 'Su pedido está en camino');

        }
    }

    public function sendMail(Event $event, $subject)
    {
        $this->logger->info('send email on_travel transition');
        /** @var User $user */
        $user = $event->getSubject()->getUser();

        $email = (new TemplatedEmail())
            ->from('admin@restajav.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate('emails/order/on_travel.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'user' => $user,
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
            'workflow.message.leave'    => 'onMessageLeave',
            'workflow.order.completed'  => 'onOrderCompletedTransition',
        ];
    }
}