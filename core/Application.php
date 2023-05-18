<?php
namespace core;

use Exception;
use PDOException;

class Application {
    public Router $router;
    public static Application $app;

    public static function init(): Application
    {
        $router = new Router();
        self::$app = new Application($router);
        return self::$app;
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