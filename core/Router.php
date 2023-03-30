<?php

namespace core;

use Couchbase\PathNotFoundException;
use http\Exception;

class Router{

    protected array $routes = [];
    protected array $params = [];

    public function get($path, $callback){
        $path = $this->convertToRegex($path);
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback){
        $path = $this->convertToRegex($path);
        $this->routes['post'][$path] = $callback;
    }

    // Chuyển path về regex
    public function convertToRegex($route):string {
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag
        return '/^' . $route . '$/i';
    }

    public function match($url, $method){
        foreach ($this->routes[$method] as $path => $action){
            if (preg_match($path, $url, $mathes)){
                foreach ($mathes as $key => $match){
                    if (is_string($key)){
                        $this->params[$key] = $match;
                    }
                }
                return $this->routes[$method][$path];
            }
        }
        return null;
    }

    public function resolve(){
        $method = Request::getMethod();
        $path = Request::getPath();
//        $callback = $this->routes[$method][$path] ?? false;
        $callback = $this->match($path, $method);
        if (!is_null($callback)){
            if (is_string($callback)){
                $this->resolveString($callback);
            }
            else if (is_callable($callback)){
                $this->resolveCallable($callback);
            }
            else if (is_array($callback)){
                echo "Is array";
                $this->resolveArray($callback);
            }
            else {
                $this->resolveError($callback, "Callback is not supported");
            }
        }
        else {
            $this->resolveError($callback, "Not found path");
        }


    }

    protected function resolveString($callback){

    }

    protected function resolveArray($callback){
        $controller = $callback['controller'];
        $action = $callback['action'];
    }

    protected function resolveCallable($callback){
        call_user_func($callback, $this->params);
    }

    protected function resolveError($callback, $error){
        echo $error;
    }
}