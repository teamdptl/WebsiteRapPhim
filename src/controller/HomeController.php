<?php
namespace app\controller;

use core\Controller;
use core\View;

class HomeController extends Controller
{
    public function homeAction()
    {
        $content = "";
        $animals = ["Cat", "Dog", "Mouse", "Pig", "Kangaroo", "ChickenDinner"];
        $content .= "<ul>";
        foreach ($animals as $ani) {
            $content .= "<li>$ani</li>";
        }
        $content .= "</ul>";
        $isLogin = false;
        View::renderTemplate("demo.html",
            [
                "content" => $content,
                "animals"=> $animals,
                "isLogin" => true,
            ]
        );
    }

    public function otherAction($name){
        View::renderTemplate("demo.html", ["content" => "<h1>Duy chào bạn $name nha hehehe</h1>"]);
    }
}
