<?php
namespace core;
abstract class Controller{
    protected array $route_params = [];

    public function __construct($params){
        $this->route_params = $params;
    }

    public function __call($methodName, $args) {
        if (method_exists($this, $methodName)){
//            call_user_func(array($this, $methodName), ...$args);
            $this->$methodName(...$args);
        }
        else {
            throw new \Exception("Method $methodName not found in ".get_class($this));
        }
    }
}