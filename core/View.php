<?php

namespace core;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View{
    // Render from file
    public static function render($view, $args = []){
        extract($args, EXTR_SKIP);
        $file = dirname(__DIR__) . "/src/view/$view";
        if (is_readable($file)) {
            require_once $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    // Render from tempalte
    public static function renderTemplate($template, $args = []){
        static $twig = null;
        if ($twig === null) {
            $loader = new FilesystemLoader(dirname(__DIR__) . '/src/view/template/');
            $twig = new Environment($loader);
        }
        echo $twig->render($template, $args);
    }

    // Replace for PHP File Include
    public static function includePHPView($view): string{
        ob_start();
        $file_path = dirname(__DIR__) . "/src/view/".$view.".php";
        include $file_path;
        return ob_get_clean();
    }
}

