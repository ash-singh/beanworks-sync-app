<?php

namespace App\Login;

class Password
{
    public static function encrypt(string $plainPassword): string
    {
        return base64_encode($plainPassword);
    }
}
