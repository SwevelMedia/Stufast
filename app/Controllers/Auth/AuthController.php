<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login', [
            'title' => 'Masuk'
        ]);
    }

    public function register()
    {
        return view('auth/register', [
            'title' => 'Daftar'
        ]);
    }
}
