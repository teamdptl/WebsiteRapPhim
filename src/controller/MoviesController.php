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

        $categories = Category::findAll();
        $tags = Tag::findAll();
        View::renderTemplate('movies\movie_page_duy.html',[
            "navbar" => $navbar,
            "categories" => $categories,
            "tags" => $tags,
        ]);
    }

    public function searchMovie()
    {
        $page = isset($_GET["currentPage"]) ? (int)$_GET["currentPage"] : 1;
        $search = $_GET["text"] ?? "";
        $category = isset($_GET["category"]) ? (int)$_GET["category"] : 0;
        $ageMin = isset($_GET["minAge"]) ? (int)$_GET["minAge"] : 0;
        $ageMax = isset($_GET["maxAge"]) ? (int)$_GET["maxAge"] : 99;
        $ratingMin = isset($_GET["ratingMin"]) ? (int)$_GET["ratingMin"] : 0;
        $ratingMax = isset($_GET["ratingMax"]) ? (int)$_GET["ratingMax"] : 10;
        $futureMovie = isset($_GET["futureMovie"]) ? (int)$_GET["futureMovie"] : 0;

        $exportMovies = $this->findMovieInDB($search, $category, $ageMin, $ageMax, $ratingMin, $ratingMax, $futureMovie);

        $resObj = new stdClass();
        $resObj->list = array_slice($exportMovies, ($page-1)*10 , 10);
        $resObj->maxPage = $this->maxPage($exportMovies);
        $resObj->activePage = $page;
        echo json_encode($resObj);
    }

    public function findMovieInDB($search, $category, $ageMin, $ageMax, $ratingMin, $ratingMax, $futureMovie){
        $databaseMovie = Movie::findAll();
        $exportMovies = [];
        foreach($databaseMovie as $movie){
            $exportMovies[] = $this->mapping($movie);
        }

        foreach ($exportMovies as $key => $movie){
            if (!str_contains(strtolower($movie->movieName), strtolower($search))){
                unset($exportMovies[$key]);
            }

            if ($category != 0){
                $isHas = false;
                foreach ($movie->categoryList as $cate){
                    if ($cate->categoryID == $category){
                        $isHas = true;
                    }
                }
                if (!$isHas){
                    unset($exportMovies[$key]);
                }
            }

//            if ($movie->rating < $ratingMin || $movie->rating > $ratingMax){
//                unset($exportMovies[$key]);
//                continue;
//            }

            if ($movie->tag->minAge < $ageMin || $movie->tag->minAge > $ageMax){
                unset($exportMovies[$key]);
            }

            $release = strtotime($movie->dateRelease);
            if ($futureMovie != 0){
                if ($release <= time())
                    unset($exportMovies[$key]);
            }
            else {
                if ($release > time())
                    unset($exportMovies[$key]);
            }
        }
        return $exportMovies;
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
        $movie->categoryNames = $listNames;
        $movie->categoryList = $categories;
        $movie->tag = Tag::find(Model::UN_DELETED_OBJ, $movie->tagID);
        return $movie;
    }

    public function findMovieByNow($isFuture = false, $limit = 5): array
    {
        $movies = Movie::findAll();
        $listReturn = [];
        foreach ($movies as $movie){
            if ($limit <= 0) break;
            $release = strtotime($movie->dateRelease);
            if (!$isFuture && $release <= time()){
                $movie = $this->mapping($movie);
                $listReturn[] = $movie;
                $limit--;
            }

            if ($isFuture && $release > time()){
                $movie = $this->mapping($movie);
                $listReturn[] = $movie;
                $limit--;
            }
        }
        return $listReturn;
    }

    public function getFeaturedMovies(): bool|array
    {
        $movies = Movie::findAll();
        $listReturn = [];
        foreach ($movies as $key => $movie){
            if ($movie->isFeatured == true){
                $movie = $this->mapping($movie);
                $listReturn[] = $movie;
            }
        }
        return $listReturn;
    }
}
