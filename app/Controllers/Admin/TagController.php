<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class TagController extends BaseController
{
    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        return view('admin/tag/index', [
            'title' => 'Tag',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }
}
