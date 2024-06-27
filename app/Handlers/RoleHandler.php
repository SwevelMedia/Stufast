<?php

namespace App\Handlers;

use Firebase\JWT\JWT;

class RoleHandler
{
    public static function getRole()
    {
        helper('cookie');

        try {
            $token = get_cookie('access_token');
            $decode = JWT::decode($token, getenv('TOKEN_SECRET'), ['HS256']);
            $role = $decode->role;
            return $role;
        } catch (\Exception $e) {
            return 'guest';
        }
    }
}
