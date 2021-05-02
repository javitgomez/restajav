<?php

namespace App\Services;

use App\Entity\Book;
use Symfony\Component\Workflow\Registry;

class BookManager
{
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }


    public function cancel(Book $form)
    {
        $workflow = $this->registry->get($form);

        if ($workflow->can($form, 'mark_as_canceled')) {
            $workflow->apply($form, 'mark_as_canceled');
        }
    }

    public function answer(Book $form)
    {
        $workflow = $this->registry->get($form);

        if ($workflow->can($form, 'mark_as_answered')) {
            $workflow->apply($form, 'mark_as_answered');
        }
    }
}

