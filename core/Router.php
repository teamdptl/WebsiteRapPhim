<?php

namespace core;
use app\controller\GlobalController;
use core\Request;
use app\model\User;
use app\utils\SessionManager;

class Router{

    protected array $routes = [];
    protected array $params = [];
    protected array $loginRequiredPath = [];
    protected array $adminRequiredPath = [];

    public function get($path, $callback, $loginRequired = false, $adminRequired = false){
        $path = $this->convertToRegex($path);
        $this->routes['get'][$path] = $callback;
        if ($loginRequired){
            $this->loginRequiredPath['get'][$path] = true;
        }
        if ($adminRequired){
            $this->adminRequiredPath['get'][$path] = true;
        }
    }

    public function post($path, $callback, $loginRequired = false, $adminRequired = false){
        $path = $this->convertToRegex($path);
        $this->routes['post'][$path] = $callback;
        if ($loginRequired){
            $this->loginRequiredPath['get'][$path] = true;
        }
        if ($adminRequired){
            $this->adminRequiredPath['get'][$path] = true;
        }
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

    protected function matchRequire($arr, $url, $method){
        if ($arr == null) return false;
        foreach ($arr[$method] as $path => $isRequire){
            if (preg_match($path, $url, $mathes) && $isRequire == true){
                return true;
            }
        }
        return false;
    }

    public function isRequireLogin($url, $method){
        return $this->matchRequire($this->loginRequiredPath,$url, $method);
    }

    public function isRequireAdmin($url, $method){
        return $this->matchRequire($this->adminRequiredPath,$url, $method);
    }

    public function resolve(){
        // Init request user
        $method = Request::getMethod();
        $path = Request::getPath();
        $callback = $this->match($path, $method);
        if (!is_null($callback)){
            // Add user to request
            $session = new SessionManager();
            if ($session->signInUserID != null){
                $user = User::find($session->signInUserID);
                if ($user != null){
                    Request::$user = $user;
                    // echo "Ban da dang nhap voi email ".$user->email;
                }
                    
            }
            // Add middleware to all route
            // GlobalController::checkRequire($this->isRequireLogin($path, $method), $this->isRequireAdmin($path, $method));
            if (is_string($callback)){
                $this->resolveString($callback);
            }
            else if (is_callable($callback)){
                $this->resolveCallable($callback);
            }
            else if (is_array($callback)){
                $this->resolveArray($callback);
            }
            else {
                throw new \Exception("Callback for $path(method: $method) is not found", 404);
            }
        }
        else {
            throw new \Exception("Path not found", 404);
        }
    }

    protected function resolveString($callback){
        View::render($callback);
    }

    protected function resolveArray($callback){
        $controller = $callback['controller'];
        $action = $callback['action'];
        // Add namesapce to string
        $controller = "\app\controller\\".$controller;
        if (class_exists($controller)){
            $controller_object = new $controller($this->params);
            try{
                $controller_object->$action(...$this->params);
            } catch (\ArgumentCountError $e){
                throw new \Exception("Method $action has different arguments or don't exist in class " .get_class($this));
            }
        }
        else {
            throw new \Exception("Class ".$controller." is not found in your project!");
        }
    }

    protected function resolveCallable($callback){
        call_user_func($callback, ...$this->params);
    }
}