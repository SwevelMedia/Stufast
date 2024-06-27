<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class CategoryController extends BaseController
{
    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        return view('admin/category/index', [
            'title' => 'Kategori',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }
}
