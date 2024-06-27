<?php

namespace App\Controllers\Company;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class HireController extends BaseController
{
    public function index()
    {
        return view('company/hire/index', [
            'title' => 'hire',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function cart()
    {
        return view('company/hire/cart', [
            'title' => 'Cart Talent',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }
}
