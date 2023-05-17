<?php 
namespace app\controller;
use app\model\Category;
use app\model\Cinema;
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
        ]);
    }

    public function getMoviesPageTest(){
        $navbar = GlobalController::getNavbar();

        $categories = Category::findAll();
        $tags = Tag::findAll();
        $cinemas = Cinema::findAll();
        View::renderTemplate('movies\movie_page_duy.html',[
            "navbar" => $navbar,
            "categories" => $categories,
            "tags" => $tags,
            "cinemas" => $cinemas,
        ]);
    }

    public function searchMovie()
    {
        $page = isset($_GET["currentPage"]) ? (int)$_GET["currentPage"] : 1;
        $search = $_GET["text"] ?? "";
        $category = isset($_GET["category"]) ? (int)$_GET["category"] : 0;
        $ageMin = isset($_GET["minAge"]) ? (int)$_GET["minAge"] : 0;
        $ageMax = isset($_GET["maxAge"]) ? (int)$_GET["maxAge"] : 99;
        $cinema = isset($_GET["cinema"]) ? (int)$_GET["cinema"] : 0;
        $futureMovie = isset($_GET["futureMovie"]) ? (int)$_GET["futureMovie"] : 0;

        $movies = $this->getMoviesInDB($search, $category, $ageMin, $ageMax, $cinema, $futureMovie);

        $resObj = new stdClass();
        $resObj->list = array_slice($movies, ($page-1)*10 , 10);
        $resObj->maxPage = $this->maxPage($movies);
        $resObj->activePage = $page;
        echo json_encode($resObj);
    }

    public function getMoviesInDB($search, $category, $ageMin, $ageMax, $cinema, $futureMovie){
        $movies = Movie::query("SELECT movie.*, tag.tagName, tag.minAge, GROUP_CONCAT(category.cateName) AS categoryList, GROUP_CONCAT(category.categoryID) AS categoryIds FROM `movie` INNER JOIN movie_category ON movie_category.movieID = movie.movieID INNER JOIN tag ON tag.tagID = movie.tagID INNER JOIN category ON category.categoryID = movie_category.categoryID GROUP BY movie.movieID;");
        foreach($movies as $key => $movie){

            // Split name
            $movie->categoryList = explode(",", $movie->categoryList);
            $movie->category = json_encode($movie->categoryList);
            $movie->categoryIds = explode(",", $movie->categoryIds);

            if (!str_contains(strtolower($movie->movieName), strtolower($search))){
                unset($movies[$key]);
            }

            if ($category != 0){
                $isHas = false;
                foreach ($movie->categoryIds as $cate){
                    if ($cate == $category){
                        $isHas = true;
                    }
                }
                if (!$isHas){
                    unset($movies[$key]);
                }
            }

            if ($movie->minAge < $ageMin || $movie->minAge > $ageMax){
                unset($movies[$key]);
            }

            $release = strtotime($movie->dateRelease);
            if ($futureMovie != 0){
                if ($release <= time())
                    unset($movies[$key]);
            }
            else {
                if ($release > time())
                    unset($movies[$key]);
            }
        }

        if ($cinema != 0){
            $cinemaMovies = Movie::query("SELECT movie.* FROM `movie` INNER JOIN showtime ON showtime.movieID = movie.movieID INNER JOIN room ON room.roomID = showtime.roomID where room.cinemaID = :cinemaId", [
                "cinemaId" => $cinema
            ]);
            foreach ($movies as $key=>$movie1){
                $isHas = false;
                foreach ($cinemaMovies as $movie2){
                    if ($movie1->movieID == $movie2->movieID){
                        $isHas = true;
                        break;
                    }
                }
                if (!$isHas){
                    unset($movies[$key]);
                }
            }
        }

        return $movies;
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
