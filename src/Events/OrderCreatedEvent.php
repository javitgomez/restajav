<?php
namespace App\Events;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class OrderCreatedEvent extends Event
{
    const ORDER_NEW_CREATED   = 'order.new';

    protected $user;
    protected $order;

    public function __construct(User $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
