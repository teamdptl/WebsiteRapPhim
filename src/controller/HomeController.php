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
        View::renderTemplate("/template/demo.html",
            [
                "content" => $content,
                "animals"=> $animals,
                "isLogin" => true,
            ]
        );
    }

    public function otherAction($name){
        View::renderTemplate("/template/demo.html", ["content" => "<h1>Duy chào bạn $name nha hehehe</h1>"]);
    }

    public function homePageRender(){
        $navbar = GlobalController::getNavbar();
        View::renderTemplate("/home/home_page.html",
            [
                "navbar" => $navbar,
            ]
        );
    }

    protected function getCarousel(){
        $arr = [
            1 => [
                "id" => 1,
                "link" => "https://bloganchoi.com/wp-content/uploads/2023/02/demon-slayer-season-3-release-date.jpg",
                "type" => "image",
            ],
            2 => [
                "id" => 2,
                "link" => "https://genk.mediacdn.vn/2019/12/2/photo-1-1575269616397390734337.jpg",
                "type" => "image",
            ],
            3 => [
                "id" => 3,
                "link" => "https://img.tapimg.net/market/images/dc0604212a746b2f231f2cd1c675ae57.jpeg?imageView2/2/w/720/h/720/q/80/format/jpg/interlace/1/ignore-error/1",
                "type" => "image",
            ]
        ];
    }
}
