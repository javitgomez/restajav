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
use Symfony\Component\Mime\Address;
use App\Repository\CustomManagerRepository;

class WorkflowLoggerSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $mailer;
    private $customManagerRepository;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, CustomManagerRepository $customManagerRepository)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->customManagerRepository = $customManagerRepository;
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
        if ($event->getTransition()->getName() === 'mark_as_on_travel') {
            $this->sendMailOnTravel($event);
            $this->logWorkFlow($event);
        }
        if ($event->getTransition()->getName() === 'mark_as_delivered') {
            $this->onOrderDelivered($event);
            $this->logWorkFlow($event);
        }
    }

    private function logWorkFlow(Event $event)
    {
        $this->logger->alert(sprintf(
            'Workflow %s (id: "%s") performed transition "%s" from "%s" to "%s"',
            $event->getWorkflowName(),
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()->getTos())
        ));
    }

    private function sendMailOnTravel(Event $event)
    {
        $this->logger->info('send email on_travel transition');
        /** @var User $user */
        $user = $event->getSubject()->getUser();

        $email = (new TemplatedEmail())
            ->from(new Address('registro@horuslegalalliance.es', 'RestaJav'))
            ->to($user->getEmail())
            ->subject('Su pedido está en camino')
            ->htmlTemplate('emails/order/on_travel.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'user' => $user,
                'customManager' =>$this->customManagerRepository->find(1),
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function onOrderDelivered(Event $event)
    {
        $this->logger->info('on Order delivered');
        /** @var User $user */
        $user = $event->getSubject()->getUser();

        $email = (new TemplatedEmail())
            ->from(new Address('registro@horuslegalalliance.es', 'RestaJav'))
            ->to($user->getEmail())
            ->subject('¡Tu ha pedido ha sido entregado!')
            ->htmlTemplate('emails/survey/index.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'user' => $user,
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
            'workflow.order.completed'  => 'onOrderCompletedTransition',
        ];
    }
}
