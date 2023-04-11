<?php

namespace app\controller;

use Core\Request;
use core\View;

class GlobalController
{

    public static function getNavbar(){
        session_start();
        $user = null;
        $user = [
            "username" => "Huỳnh Khánh Duy",
            "email" => "huykhaduy@gmail.com",
            "isAdmin" => true,
        ];
        $navItems = [
            1 => [
                "navID" => 1,
                "navContent" => "Trang chủ",
                "navHref" => "/"
            ],
            2 => [
                "navID" => 2,
                "navContent" => "Phim",
                "navHref" => "/movies"
            ],
            3 => [
                "navID" => 3,
                "navContent" => "Rạp",
                "navHref" => "/cinemas"
            ],
            4 => [
                "navID" => 4,
                "navContent" => "Khuyến mãi",
                "navHref" => "/promotions"
            ],
            5 => [
                "navID" => 5,
                "navContent" => "Liên hệ",
                "navHref" => "/contact"
            ]
        ];
        $path = Request::getPath();
        return View::renderTemplateStr("/template/navbar.html",
            [
                "user" => $user,
                "navItems" => $navItems,
                "path" => $path
            ]
        );
    }

    public static function getFooter(){

    }

    public static function checkRequire($isPathLogin = false, $isPathAdmin = false){
        if (!$isPathLogin && !$isPathAdmin){
            return;
        }

        if ($isPathLogin){
            $isLogined = self::isUserLogin();
            if ($isLogined == false){
                // Render error page
                echo 'Error: Do not have permission';
                Request::redirect("/");
            }
        }

        if ($isPathAdmin){
            $isAdmin = self::isUserAdmin();
            if ($isAdmin == false){
                // Render error page
                echo 'Error: Do not have permission';
                Request::redirect("/");
            }
        }
    }

    public static function isUserLogin(){
        return true;
    }

    public static function isUserAdmin(){
        return true;
    }
}