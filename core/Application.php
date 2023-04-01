<?php
namespace core;

use Exception;

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
        } catch (Exception $e) {
            echo "<pre>";
//            View::renderTempalte("error.html");
            echo $e;
            echo "</pre>";
        }
    }
}