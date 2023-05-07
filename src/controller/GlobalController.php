<?php

namespace app\controller;

use core\Request;
use core\View;

class GlobalController
{

    public static function getNavbar(){
        $user = Request::$user;
        if ($user != null){
            $user->isAdmin = false;
        }
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

    public static function getNavAdmin() {
        $user = Request::$user;
        if ($user != null){
            $user->isAdmin = false;
        }
        $navItems = [
            1 => [
                "navID" => 1,
                "navContent" => "Quản lý Phim",
                "navHref" => "/adminQuanLyPhim"
            ],
            2 => [
                "navID" => 2,
                "navContent" => "Quản lý Lịch Chiếu",
                "navHref" => "/adminQuanLyLichChieu"
            ],
            3 => [
                "navID" => 3,
                "navContent" => "Quản lý Thức Ăn",
                "navHref" => "/adminQuanLyThucAn"
            ],
            4 => [
                "navID" => 4,
                "navContent" => "Quản lý hóa đơn giảm giá",
                "navHref" => "/adminQuanLyHoaDonGiamGia"
            ],
            5 => [
                "navID" => 5,
                "navContent" => "Quản lý Tài Khoản",
                "navHref" => "/adminQuanLyTaiKhoan"
            ],
            6 => [
                "navID" => 6,
                "navContent" => "Quản lý Quyền",
                "navHref" => "/adminQuanLyQuyen"
            ],
            7 => [
                "navID" => 7,
                "navContent" => "Thống kê",
                "navHref" => "/adminThongKe"
            ]
        ];
        $path = Request::getPath();
        return View::renderTemplateStr("/template/navAdmin.html",
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