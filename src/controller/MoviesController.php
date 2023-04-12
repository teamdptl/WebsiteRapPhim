<?php 
namespace app\controller;
use core\Controller;
use core\View;


class MoviesController extends Controller{
    public function getMoviesPage(){
        $navbar = GlobalController::getNavbar();
        $listMovies = self::getMovies();
        View::renderTemplate('movies\movies_page.html',[
            "navbar" => $navbar,
            "listMovie" => $listMovies,
        ]);


    }
    protected function getMovies(){
        $arr = [
            [
                "id" => 0,
                "link" => "https://img.redbull.com/images/c_crop,x_454,y_0,h_933,w_700/c_fill,w_400,h_540/q_auto:low,f_auto/redbullcom/2020/3/31/xc6e14jeolutgs3mlajn/ori-and-the-will-of-the-wisps-screenshot",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
              
            ],
            [
                "id" => 1,
                "link" => "https://d1j8r0kxyu9tj8.cloudfront.net/images/1566809317niNpzY2khA3tzMg.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 2,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/71go9c0DQnL._AC_SY741_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 3,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 5,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
            [
                "id" => 4,
                "link" => "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
                "type" => "Hành Động , Hài Hước",
                "name" => "RockManX4"
            ],
        ];
        return $arr;
    }
}


?>