<?php
namespace app\controller;
use app\utils\Database;
use core\Controller;
use core\View;
use app\model\Movie;
use app\model\Room;
use app\model\Showtime;
use app\model\Cinema;
use core\Model;
use app\model\Tag;
use app\model\Category;
class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        $date = $_GET['date'];
        
        var_dump($date);
        var_dump("1");
        $navbar = GlobalController::getNavbar();
        $movieDetail = Movie::find(Model::UN_DELETED_OBJ,$id);
   
        $tag =  Tag::find(Model::UN_DELETED_OBJ, $movieDetail->tagID);
        // $listCinema = Cinema::findAll(Model::ALL_OBJ);
        $listCinema =  Cinema::query("SELECT DISTINCT cinema.*  FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID AND showtime.movieID = :id;", [
            "id" => $id
        ]);
        $showtimeList =  Showtime::query("SELECT showtime.* , cinema.cinemaID FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID  and showtime.timeStart BETWEEN '$date 00:00:00' and '$date 23:59:59' AND showtime.movieID =:id ;", [
            "id" => $id]);
            var_dump($showtimeList);
        $categoryList =  Category::query("SELECT category.* FROM movie_category ,category WHERE movieID = :id AND movie_category.categoryID = category.categoryID", [
                "id" => $id]);
        // var_dump($listCinema);
        View::renderTemplate('detailMovie/detail-movie.html', [
            "id" =>$id,
            "navbar" => $navbar,
            "movieDetail" => $movieDetail,
            "tag" =>$tag, 
            "listCinema" => $listCinema,
            "showtimeList" =>  $showtimeList,
            "categoryList" => $categoryList,
        ]);
    }
    public function renderShowtime(){

    }
    
}

?>