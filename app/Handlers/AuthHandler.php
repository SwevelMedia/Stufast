<?php

namespace App\Handlers;

use Firebase\JWT\JWT;

class AuthHandler
{
    public static function decodeJwtToken($request)
    {
        try {
            $header = $request->getServer('HTTP_AUTHORIZATION');
            $token = JWT::decode(explode(' ', $header)[1], getenv('TOKEN_SECRET'), ['HS256']);
            return $token;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function user($request, $property = null)
    {
        try {
            $header = $request->getServer('HTTP_AUTHORIZATION');
            $token = JWT::decode(explode(' ', $header)[1], getenv('TOKEN_SECRET'), ['HS256']);
            return $token->{$property};
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getUser()
    {
        try {
            helper('cookie');
            $header = get_cookie('access_token');
            $token = JWT::decode($header, getenv('TOKEN_SECRET'), ['HS256']);
            return $token;
        } catch (\Exception $e) {
            return null;
        }
    }
}
