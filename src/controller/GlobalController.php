<?php

namespace app\controller;

use app\model\GroupPermission;
use core\Request;
use core\View;

class GlobalController
{
    public static function getNavbar(){
        $user = Request::$user;
        if ($user != null){
            if ($user->permissionID == 2){
                $user->isAdmin = true;
            } else {
                $user->isAdmin = false;
            }
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
            if ($user->permissionID == 2){
                $user->isAdmin = true;
            } else {
                $user->isAdmin = false;
            }
        }
        $navItems = [
            1 => [
                "navID" => 1,
                "navContent" => "Quản lý Phim",
                "navHref" => "/adminQuanLyPhim",
                "icon" => "bx bx-movie bx-tada",
            ],
            2 => [
                "navID" => 2,
                "navContent" => "Quản lý Lịch Chiếu",
                "navHref" => "/adminQuanLyLichChieu",
                "icon" => "bx bx-calendar-week bx-tada"
            ],
            3 => [
                "navID" => 3,
                "navContent" => "Quản lý Thức Ăn",
                "navHref" => "/adminQuanLyThucAn",
                "icon" => "bx bx-food-menu bx-tada"
            ],
            4 => [
                "navID" => 4,
                "navContent" => "Quản lý hóa đơn giảm giá",
                "navHref" => "/adminDonHang",
                "icon" => "bx bx-wallet-alt bx-tada"
            ],
            5 => [
                "navID" => 5,
                "navContent" => "Quản lý Tài Khoản",
                "navHref" => "/adminQuanLyTaiKhoan",
                "icon" => "bx bxs-user-account bx-tada"
            ],
            6 => [
                "navID" => 6,
                "navContent" => "Quản lý Quyền",
                "navHref" => "/adminQuanLyQuyen",
                "icon" => "bx bx-group bx-tada"
            ],
            7 => [
                "navID" => 7,
                "navContent" => "Thống kê",
                "navHref" => "/adminThongKe",
                "icon" => "bx bx-pie-chart-alt-2 bx-tada"
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
                echo "redirect";
                Request::redirect("/signin");
            }
        }

        if ($isPathAdmin){
            $isAdmin = self::isUserAdmin();
            if ($isAdmin == false){
                echo "redirect";
                Request::redirect("/signin");
            }
        }
    }

    public static function isUserLogin(){
        if (Request::$user == null)
            return false;
        return true;
    }

    public static function isUserAdmin(){
        if (!self::isUserLogin())
            return false;
        if (Request::$user->permissionID == 2)
            return true;
        return false;
    }
}