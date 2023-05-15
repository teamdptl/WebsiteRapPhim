<?php

namespace app\controller;

use core\View;

class AdminQuanLyTaiKhoanController
{
    public function getPage(){
        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        View::renderTemplate("/admin/admin_user_manager.html", [
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
        ]);
    }
}