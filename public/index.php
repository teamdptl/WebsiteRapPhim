<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use core\Application;
use app\model\Movie;
use app\model\User;
use core\View;
// Initialize application
$app = Application::init();

$app->router->get('/hi', "example.html");
$app->router->get('/test', ["controller"=>"HomeController", "action"=>"homeAction"]);
$app->router->get('/', ["controller"=>"HomeController", "action"=>"homePageRender"]);
$app->router->get('/name/{name:\w+}', ["controller"=>"HomeController", "action"=>"otherAction"]);
$app->router->get('/page/{id:\d+}', function($id){ 
    echo "Bạn đang truy cập trang $id";
});

$app->router->get('/phuc', function(){ 
    View::render('dangnhap.php', ["username"=> "Phuc"]);
});

$app->router->get('/signin', ['controller' => 'SignInController', 'action' => 'getSignPage']);
$app->router->get('/signup', ['controller' => 'SignUpController', 'action' => 'getSignUpPage']);

$app->router->get('/{error:\S+}', function($error){
    echo "Bạn đang truy cập trang $error không tồn tại"."<br>";
    echo "<a href='/'>Click vào đây để trở về</a>";
});

$app->router->post('/signin', ["controller" => "SignInController", "action" => "validateLogin"]);
$app->router->post('/signup', ["controller" => "SignUpController", "action" => "validateLogup"]);

// Running and resolver
$app->run();

// $movie = Movie::find("movie100");
// var_dump($movie);