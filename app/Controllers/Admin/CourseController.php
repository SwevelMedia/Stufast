<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class CourseController extends BaseController
{
    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        return view('admin/course/index', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function create()
    {
        return view('admin/course/create', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function show()
    {
        return view('admin/course/show', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function video()
    {
        return view('admin/course/video', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function quiz()
    {
        return view('admin/course/quiz', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }

    public function member()
    {
        return view('admin/course/member', [
            'title' => 'Kursus',
            'user' => AuthHandler::getUser(),
            'role' => RoleHandler::getRole()
        ]);
    }
}
