<?php

namespace app\controller;

use core\Request;
use app\model\User;
use app\utils\SessionManager;


class LogoutController {
    public function logoutHandle(){
        $session = new SessionManager();
        $session->signInUserID = null;
        Request::redirect("/");
    }
}