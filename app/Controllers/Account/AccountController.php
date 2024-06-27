<?php

namespace App\Controllers\Account;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class AccountController extends BaseController
{
    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        return view('account/profile', [
            'title' => 'Profil',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }
}
