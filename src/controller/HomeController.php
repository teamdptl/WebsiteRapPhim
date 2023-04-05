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
        $listMovie = self::getCarousel();
        View::renderTemplate("/home/home_page.html",
            [
                "navbar" => $navbar,
                "listMovie" => json_encode($listMovie),
            ]
        );
    }

    protected function getCarousel(){
        $arr = [
            [
                "id" => 0,
                "link" => "https://cdnmedia.baotintuc.vn/Upload/DmtgOUlHWBO5POIHzIwr1A/files/2022/11/07/Black-Adam-07112022.jpg",
                "type" => "image",
            ],
            [
                "id" => 1,
                "link" => "https://talkfirst.vn/wp-content/uploads/2022/06/describe-your-favorite-movie-avengers-endgame-1024x576.jpg",
                "type" => "image",
            ],
            [
                "id" => 2,
                "link" => "https://bloganchoi.com/wp-content/uploads/2023/02/demon-slayer-season-3-release-date.jpg",
                "type" => "image",
            ]
        ];
        return $arr;
    }
}
