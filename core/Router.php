<?php

namespace core;

use Couchbase\PathNotFoundException;
use http\Exception;

class Router{

    protected array $routes = [];

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }

    public function resolve(){
        $method = Request::getMethod();
        $path = Request::getPath();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback == false){
            echo "Error";
        }
        if (is_string($callback)){
            echo "Call back is string";
        }
        if (is_callable($callback)){
            echo "Call back is callable";
        }
        if (is_array($callback)){
            echo "Call back is array";
        }
    }
}