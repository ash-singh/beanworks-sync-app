<?php

namespace App\Message\Event;

use App\Login\Credentials;

class LoggedIn
{
    /**
     * @var Credentials
     */
    public $credentials;

    /**
     * @var string
     */
    public $token;

    public function __construct(Credentials $credentials, string $token)
    {
        $this->credentials = $credentials;
        $this->token = $token;
    }
}
