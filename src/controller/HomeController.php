<?php
namespace app\controller;

use core\Controller;
use core\View;

class HomeController extends Controller
{
    public function homeAction()
    {
        $content = "";
        $data = ["Cat", "Dog", "Mouse", "Pig", "Kangaroo", "Chicken"];
        $content .= "<ul>";
        foreach ($data as $d) {
            $content .= "<li>$d</li>";
        }
        $content .= "</ul>";
        View::renderTemplate("home.html", ["content" => $content]);
    }

    public function otherAction($name){
        View::renderTemplate("home.html", ["content" => "<h1>Duy chào bạn $name nha hehehe</h1>"]);
    }
}
