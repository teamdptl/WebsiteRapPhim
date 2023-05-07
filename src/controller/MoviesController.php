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
        $listMovie = Movie::findAll(Model::ALL_OBJ);
        // $listCategory =Category::findAll(Model::ALL_OBJ);
        View::renderTemplate('movies\movies_page.html',[
            "navbar" => $navbar,
            "listMovie" => $listMovie,
            // "listCategory" => $listCategory,
        ]);
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
