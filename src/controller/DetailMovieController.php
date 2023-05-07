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
class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        $navbar = GlobalController::getNavbar();
        $movieDetail = Movie::find(Model::UN_DELETED_OBJ,$id);
   
        $tag =  Tag::find(Model::UN_DELETED_OBJ, $movieDetail->tagID);
        // $listCinema = Cinema::findAll(Model::ALL_OBJ);
        $listCinema =  Cinema::query("SELECT DISTINCT cinema.*  FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID AND showtime.movieID = :id;", [
            "id" => $id
        ]);
        $showtimeList =  Showtime::query("SELECT showtime.* , cinema.cinemaID FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID AND showtime.movieID =:id ;", [
            "id" => $id]);
        // var_dump($listCinema);
        View::renderTemplate('detailMovie/detail-movie.html', [
            "navbar" => $navbar,
            "movieDetail" => $movieDetail,
            "tag" =>$tag, 
            "listCinema" => $listCinema,
            "showtimeList" =>  $showtimeList,
            // "cinema" =>$cinema
        ]);
    }
    
}

?>