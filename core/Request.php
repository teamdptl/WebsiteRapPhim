<?php

namespace core;

use app\model\User;

class Request {
    public static string $GET = "get";
    public static string $POST = "post";
    public static User $user;

    public static function getMethod(): string {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function getPath(): string {
        return $_SERVER['PATH_INFO'] ?? '/';
    }

    public static function redirect($path){
        header("Location: $path");
    }

    public static function getQuery(): string {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    public static function getQueryArr(): array{
        // explode dùng để tách chuỗi
        $splitArr = explode('&', $_SERVER['QUERY_STRING']);
        $arr = [];
        foreach ($splitArr as $item){
            $subItem = explode('=', $item);
            $arr[$subItem[0]] = $subItem[1];
        }
        return $arr;
    }

    public static function getLoginCookie(){
        return $_COOKIE["loginCookie"] ?? null;
    }

    public static function retrieveUser($loginCookie){

    }
}