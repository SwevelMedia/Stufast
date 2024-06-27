<?php

namespace App\Controllers\Member;

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
        return view(
            'member/course/index',
            [
                'title' => 'Kursus',
                'role' => RoleHandler::getRole(),
                'user' => AuthHandler::getUser()
            ]
        );
    }

    public function show($id = null)
    {
        return view(
            'member/course/show',
            [
                'title' => 'Pembelajaran',
                'role' => RoleHandler::getRole(),
                'user' => AuthHandler::getUser()
            ]
        );
    }

    public function certificate()
    {
        return view(
            'member/course/certificate',
            [
                'title' => 'Sertifikat',
                'role' => RoleHandler::getRole(),
                'user' => AuthHandler::getUser()
            ]
        );
    }
}
