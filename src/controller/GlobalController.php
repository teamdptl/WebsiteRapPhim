<?php

namespace app\controller;

use app\model\GroupPermission;
use core\Application;
use core\Request;
use core\View;

class GlobalController
{
    public static function getNavbar(){
        $user = Request::$user;
        if ($user != null){
            if ($user->permissionID != 1){
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
        ];
        $path = Request::getPath();
        return View::renderTemplateStr("/template/navbar.html",
            [
                "user" => $user,
                "navItems" => $navItems,
                "path" => $path,
                "adminNav" => static::getAdminNavForGroup()[0] ?? [],
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
        $navItems = static::getAdminNavForGroup();
        $path = Request::getPath();
        return View::renderTemplateStr("/template/navAdmin.html",
            [
                "user" => $user,
                "navItems" => $navItems,
                "path" => $path,
            ]
        );
    }

    public static function getAdminNavForGroup(){
        $navItems = [
            1 => [
                "navID" => 1,
                "navContent" => "Quản lý Phim",
                "navHref" => "/adminQuanLyPhim",
                "icon" => "bx bx-movie bx-tada",
                "userPermission" => [Application::$quanly, Application::$nhanvien],
            ],
            2 => [
                "navID" => 2,
                "navContent" => "Quản lý Lịch Chiếu",
                "navHref" => "/adminShowTime",
                "icon" => "bx bx-calendar-week bx-tada",
                "userPermission"=> [Application::$quanly, Application::$nhanvien]
            ],
            3 => [
                "navID" => 3,
                "navContent" => "Quản lý Thức Ăn",
                "navHref" => "/adminFood",
                "icon" => "bx bx-food-menu bx-tada",
                "userPermission"=> [Application::$quanly, Application::$nhanvien]
            ],
            4 => [
                "navID" => 4,
                "navContent" => "Quản lý hóa đơn",
                "navHref" => "/adminDonHang",
                "icon" => "bx bx-wallet-alt bx-tada",
                "userPermission"=> [Application::$quanly]
            ],
            5 => [
                "navID" => 5,
                "navContent" => "Quản lý Tài Khoản",
                "navHref" => "/adminQuanLyTaiKhoan",
                "icon" => "bx bxs-user-account bx-tada",
                "userPermission"=> [Application::$quanly, Application::$admin]
            ],
            7 => [
                "navID" => 7,
                "navContent" => "Thống kê",
                "navHref" => "/adminThongKe",
                "icon" => "bx bx-pie-chart-alt-2 bx-tada",
                "userPermission"=> [Application::$quanly]
            ]
        ];

        if (Request::$user == null){
            return [];
        }

        $listNav = [];

        foreach ($navItems as $nav){
            $isExist = false;
            $permissions = $nav["userPermission"] ?? [];
            foreach ($permissions as $permiss){
                if ($permiss !=null && $permiss->permissionID == Request::$user->permissionID){
                    $isExist = true;
                    break;
                }
            }

            if ($isExist){
                $listNav[] = $nav;
            }
        }

        return $listNav;
    }

    public static function checkAuthority($arrUserPermissions){

        if (count($arrUserPermissions) == 0)
            return;

        foreach($arrUserPermissions as $group){
            if ($group->permissionID == Request::$user->permissionID){
                return;
            }
        }

        http_response_code(400);

        if (Request::$user == null){
            Request::redirect("/signin");
            exit();
        }

        Request::redirect("/");
        exit();
    }

    public static function checkRequire($isPathLogin = false, $isPathAdmin = false){
        if (!$isPathLogin && !$isPathAdmin){
            return;
        }

        if ($isPathLogin){
            $isLogined = self::isUserLogin();
            if ($isLogined == false){
                Request::redirect("/signin");
            }
        }

        if ($isPathAdmin){
            $isAdmin = self::isUserAdmin();
            if ($isAdmin == false){
                header('Location: ' . $_SERVER['HTTP_REFERER']);
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

    public function checkUserRight($featureGroup){
        $user = Request::$user;
    }
}