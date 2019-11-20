<?php

namespace App\Login\Exception;

use Symfony\Component\Security\Core\Exception\BadCredentialsException as credentialsException;

class BadCredentialsException extends credentialsException
{
}
