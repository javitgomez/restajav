<?php
namespace App\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegistrationEvent extends Event
{
    const USER_NEW_SIGNUP          = 'user.new';
    const CHANGE_PASSWORD   = 'user.change.password';
    const CONFIRMED_EMAIL   = 'user.confirmed.email';

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser() : User
    {
        return $this->user;
    }
}
