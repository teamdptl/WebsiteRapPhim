<?php 
namespace app\controller;
use app\model\Category;
use app\model\Movie;
use app\model\MovieCategory;
use app\model\Tag;
use core\Controller;
use core\Model;
use core\View;
use stdClass;


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

    public function getMoviesPageTest(){
        $navbar = GlobalController::getNavbar();
        $databaseMovie = Movie::findAll(Model::UN_DELETED_OBJ);
        $listMovies = array_slice($databaseMovie,0, 10);
        $exportMovies = [];

        foreach($listMovies as $movie){
            $exportMovies[] = $this->mapping($movie);
        }

        $categories = Category::findAll(Model::UN_DELETED_OBJ);
        $tags = Tag::findAll(Model::UN_DELETED_OBJ);
        View::renderTemplate('movies\movie_page_duy.html',[
            "navbar" => $navbar,
            "listMovie" => $exportMovies,
            "categories" => $categories,
            "tags" => $tags,
            "maxPage" => $this->maxPage($databaseMovie)
        ]);
    }

    public function searchMovie(){
        $page = $_POST["page"];
        $search = $_POST["text"];
        $category = $_POST["category"];
        $ageMin = $_POST["minAge"];
        $ageMax = $_POST["maxAge"];
        $ratingMin = $_POST["ratingMin"];
        $ratingMax = $_POST["ratingMax"];

        echo $page;
        echo $category;
        echo $search;
        echo $ageMin;
        echo $ageMax;
        echo $ratingMin;
        echo $ratingMax;
    }

    public function maxPage(array $listMovie, int $perPage = 10){
        return ceil(count($listMovie)/10);
    }

    public function mapping($movie){
        $categories = $movie->hasList(MovieCategory::class);
        $listNames = [];
        foreach ($categories as $movCategory){
            $category = Category::find(Model::UN_DELETED_OBJ, $movCategory->categoryID);
            $listNames[] = $category->cateName;
        }
        $movie->category = json_encode($listNames);
        $movie->tag = Tag::find(Model::UN_DELETED_OBJ, $movie->tagID)->tagName;
        $movie->posterLink = "https://themoviedb.org/t/p/w600_and_h900_bestv2/".$movie->posterLink;
        return $movie;
    }
}
