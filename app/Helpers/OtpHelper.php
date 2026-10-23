<?php

namespace App\Helpers;

class OtpHelper
{
    public static function generateOTP(): int
    {
        return random_int(100000, 999999);
    }
}
