<?php
namespace core;
abstract class Controller{
    protected array $route_params = [];

    public function __construct($params){
        $this->route_params = $params;
    }

    // Cái hàm này chưa cần thiết, nó không có tác dụng gì cả
    public function __call($methodName, $args) {
        if (method_exists($this, $methodName)){
            $this->$methodName(...$args);
        }
        else {
            throw new \Exception("Method $methodName not found in ".get_class($this));
        }
    }
}