<?php
namespace app\controller;

use core\Controller;
use core\View;

class HomeController extends Controller
{
    public function homeAction()
    {
        $content = "";
        $data = ["Cat", "Dog", "Mouse", "Pig", "Kangaroo"];
        $content .= "<ul>";
        foreach ($data as $d) {
            $content .= "<li>$d</li>";
        }
        $content .= "</ul>";
        View::renderTemplate("home.html", ["content" => $content]);
    }

    public function otherAction($name){
        View::renderTemplate("home.html", ["content" => "Duy chào bạn $name nha hehehe"]);
    }
}
