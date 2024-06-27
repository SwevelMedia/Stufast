<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Handlers\AuthHandler;
use App\Handlers\RoleHandler;

class HireOfferController extends BaseController
{
    public function history()
    {
        return view('member/hire/index', [
            'title' => 'penawaran',
            'role' => RoleHandler::getRole(),
            'user' => AuthHandler::getUser()
        ]);
    }
}
