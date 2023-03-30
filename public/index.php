<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use core\Application;

// Initalize application
$app = Application::init();

$app->router->get('/page/{id:\d+}', function($params){
    echo $params["id"];
});


// Running and resolver
$app->run();

