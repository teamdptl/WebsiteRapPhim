<?php

namespace app\controller;

use core\Application;
use core\Controller;
use core\Request;
use app\model\User;
use app\utils\SessionManager;


class LogoutController extends Controller {
    public function logoutHandle(){
        $session = new SessionManager();
        $session->signInUserID = null;
        Request::redirect("/");
    }

    public function hasAuthority(): array
    {
        return [Application::$user, Application::$admin, Application::$quanly, Application::$nhanvien];
    }
}