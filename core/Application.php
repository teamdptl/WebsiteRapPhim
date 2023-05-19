<?php
namespace core;

use app\model\GroupPermission;
use Exception;
use PDOException;

class Application {
    public Router $router;
    public static Application $app;
    public static GroupPermission $user;
    public static GroupPermission $admin;
    public static GroupPermission $nhanvien;
    public static GroupPermission $quanly;

    public static function init(): Application
    {
        $router = new Router();
        self::$app = new Application($router);
        self::initUser();
        return self::$app;
    }

    public static function initUser(){
        static::$user = GroupPermission::find(Model::UN_DELETED_OBJ, 1);
        static::$quanly = GroupPermission::find(Model::UN_DELETED_OBJ, 2);
        static::$admin = GroupPermission::find(Model::UN_DELETED_OBJ, 3);
        static::$nhanvien = GroupPermission::find(Model::UN_DELETED_OBJ, 4);
    }

    public function __construct(Router $router){
        $this->router = $router;
    }

    public function run(){
        try {
            $this->router->resolve();
        }  catch(PDOException $e){
            http_response_code(500);
            View::renderTemplate("/template/404.html", [
                "errorText" => "Database bị lỗi",
                "bugTracking" => $e
            ]);
        }
        catch (Exception $e) {
            View::renderTemplate("/template/404.html", [
                "errorText" => "Lỗi lập trình",
                "bugTracking" => $e
            ]);
        }
    }
}