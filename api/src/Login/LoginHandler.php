<?php

namespace App\Login;

class LoginHandler
{
    public function verify(Credentials $credentials): bool
    {
        return 'admin' == $credentials->getUserName()
            && 'admin' == $credentials->getPassword();
    }
}
