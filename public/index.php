<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use core\Application;

// Initialize application
$app = Application::init();

$app->router->get('/hi', "example.html");

$app->router->get('/', ["controller"=>"HomeController", "action"=>"homeAction"]);
$app->router->get('/test', ["controller"=>"HomeController", "action"=>"homePageRender"]);

$app->router->get('/{name:\w+}', ["controller"=>"HomeController", "action"=>"otherAction"]);

$app->router->get('/page/{id:\d+}', function($id){
    echo "Bạn đang truy cập trang $id";
});


// Running and resolver
$app->run();

//$movie = Movie::find("movie100");
//var_dump($movie);