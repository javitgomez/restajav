<?php

namespace App\Services;

use App\Entity\ContactForm;
use Symfony\Component\Workflow\Registry;

class ContactFormManager
{
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function answer(ContactForm $contactForm)
    {
        $workflow = $this->registry->get($contactForm);

        if ($workflow->can($contactForm, 'mark_as_answered')) {
            $workflow->apply($contactForm, 'mark_as_answered');
        }
    }
}
