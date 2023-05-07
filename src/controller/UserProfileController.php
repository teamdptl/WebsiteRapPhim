<?php

namespace app\controller;

use core\Request;
use core\View;

class UserProfileController
{
    public function getProfilePage(){
        $navBar = GlobalController::getNavbar();

        View::renderTemplate("profile/userProfileOrder.html", [
            "navbar" => $navBar,
            "orderPage" => true,
            "content" => "HELLO WORLD"
        ]);
    }

    public function getProfilePassword(){
        $navBar = GlobalController::getNavbar();
        View::renderTemplate("profile/userProfileChangePass.html", [
            "navbar" => $navBar,
            "passwordPage" => true,
            "content" => "HELLO WORLD"
        ]);
    }
}