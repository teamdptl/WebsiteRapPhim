<?php

namespace app\controller;

use core\Request;
use core\View;

class UserProfileController
{
    public function getProfilePage(){
        // Kiểm tra xem đã đăng nhập chưa mới cho vào trang web
        $user = Request::$user;
        if ($user == null){
            Request::redirect("/");
            return;
        }

        View::renderTemplate("profile/userProfile.html", [
            "content" => "HELLO WORLD"
        ]);

    }
}