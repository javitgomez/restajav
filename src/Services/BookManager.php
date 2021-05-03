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


    public function cancel(Book $book)
    {
        $workflow = $this->registry->get($book);

        if ($workflow->can($book, 'mark_as_canceled')) {
            $workflow->apply($book, 'mark_as_canceled');
        }
    }

    public function answer(Book $book)
    {
        $workflow = $this->registry->get($book);

        if ($workflow->can($book, 'mark_as_answered')) {
            $workflow->apply($book, 'mark_as_answered');
        }
    }
}
