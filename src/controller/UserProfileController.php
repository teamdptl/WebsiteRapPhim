<?php

namespace app\controller;

use core\Request;
use core\View;

class UserProfileController
{
    public function getProfilePage(){
        // Kiểm tra xem đã đăng nhập chưa mới cho vào trang web
//        $user = Request::$user;
//        if ($user == null){
//            Request::redirect("/");
//            return;
//        }
        $navBar = GlobalController::getNavbar();

        View::renderTemplate("profile/userProfile.html", [
            "navbar" => $navBar,
            "content" => "HELLO WORLD"
        ]);

    }
}