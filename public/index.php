<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use core\Application;

// Initalize application
$app = Application::init();

$app->router->get("/", function(){
    echo "Hello world";
});

$app->router->get("/1", "index");

$app->router->get("/2", ["controller"=>"Home", "action"=>"Do it"]);

// Running and resolver
$app->run();