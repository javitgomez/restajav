<?php

namespace App\Services;

use App\Entity\Order;
use Symfony\Component\Workflow\Registry;

class OrderManager
{
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }


    public function cancel(Order $order)
    {
        $workflow = $this->registry->get($order);

        if ($workflow->can($order, 'mark_as_canceled')) {
            $workflow->apply($order, 'mark_as_canceled');
        }
    }

    public function pending(Order $order)
    {
        $workflow = $this->registry->get($order);

        if ($workflow->can($order, 'mark_as_pending')) {
            $workflow->apply($order, 'mark_as_pending');
        }
    }

    public function on_travel(Order $order)
    {
        $workflow = $this->registry->get($order);

        if ($workflow->can($order, 'mark_as_on_travel')) {
            $workflow->apply($order, 'mark_as_on_travel');
        }
    }

    public function delivered(Order $order)
    {
        $workflow = $this->registry->get($order);

        if ($workflow->can($order, 'mark_as_delivered')) {
            $workflow->apply($order, 'mark_as_delivered');
        }
    }
}
