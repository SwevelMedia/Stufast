<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class CvController extends BaseController
{
    public function __construct()
    {
        helper('cookie');
    }

    public function index()
    {
        return view('member/cv/index', [
            'title' => 'Curriculum Vitae',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function profile()
    {
        return view('member/cv/profile', [
            'title' => 'Data Pribadi',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function experience()
    {
        return view('member/cv/experience', [
            'title' => 'Data Pengalaman',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function education()
    {
        return view('member/cv/education', [
            'title' => 'Data Pendidikan',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function achievement()
    {
        return view('member/cv/achievement', [
            'title' => 'Sertifikasi',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function portofolio()
    {
        return view('member/cv/portofolio', [
            'title' => 'Portofolio',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }

    public function download()
    {
        return view('member/cv/print');
    }
}
