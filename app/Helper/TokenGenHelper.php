<?php

namespace App\Helper;

class TokenGenHelper{
    public function generateToken($user_id)
    {
        $expirationDate = now()->addHours(2);
        $signature = hash_hmac('sha512',$user_id . '|' . $expirationDate,env('APP_AUTH_KEY'),true);
        $token = $this->base64UrlEncode($signature);

        return ['token'=>$token,'expiration_date'=>$expirationDate];
    }

    function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}