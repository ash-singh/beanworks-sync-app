<?php

namespace App\Message\Event;

use App\Document\User;
use App\Login\Credentials;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginFailed
{
    /**
     * @var Credentials
     */
    public $credentials;

    /** @var AuthenticationException */
    public $cause;

    /** @var User */
    public $user;

    public function __construct(Credentials $credentials, AuthenticationException $cause, ?User $user)
    {
        $this->credentials = $credentials;
        $this->cause = $cause;
        $this->user = $user;
    }
}
