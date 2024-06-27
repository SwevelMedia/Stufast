<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class TypeController extends BaseController
{
    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        return view('admin/type/index', [
            'title' => 'Tipe',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }
}
