<?php
namespace App\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegistrationEvent extends Event
{
    const USER_NEW    = 'user.new';
    const CHANGE_PASSWORD = 'user.change.password';

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
