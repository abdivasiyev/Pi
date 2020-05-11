<?php

namespace app\helpers;

class Security {

    public static function generateAuthToken()
    {
        $current_time = time();
        $token = dechex($current_time) . bin2hex(random_bytes(64));

        return $token;
    }

    public static function generatePasswordHash($password, $cost = 10)
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => $cost
        ]);
    }

    public static function verifyPassword($hash, $password)
    {
        return password_verify($password, $hash);
    }
}