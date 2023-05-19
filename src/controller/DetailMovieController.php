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
use Exception;
class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date('Y/m/d', time());
        $currentTime = date('Y/m/d H:i:s', time());
        $navbar = GlobalController::getNavbar();
        $movieDetail = Movie::find(Model::UN_DELETED_OBJ,$id);
   
        $tag =  Tag::find(Model::UN_DELETED_OBJ, $movieDetail->tagID);
        $listCinema =  Cinema::query("SELECT DISTINCT cinema.*  FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID AND showtime.timeStart BETWEEN '$currentTime' and '$date 23:59:59' AND showtime.movieID = :id;", [
            "id" => $id
        ]);
        $showtimeList =  Showtime::query("SELECT showtime.* , cinema.cinemaID FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID  and showtime.timeStart BETWEEN '$currentTime' and '$date 23:59:59' AND showtime.movieID =:id ;", [
            "id" => $id]);
         
        $categoryList =  Category::query("SELECT category.* FROM movie_category ,category WHERE movieID = :id AND movie_category.categoryID = category.categoryID", [
                "id" => $id]);
     
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
    public function renderShowTime($id){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date=$_POST["date"];
        $currentTime = date('Y/m/d', time());
        if(strtotime($date) > strtotime($currentTime)){
            $currentTime = '00:00:00';
        }else{
            $currentTime = date('H:i:s', time());
        }
        $listCinema =  Cinema::query("SELECT DISTINCT cinema.*  FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID AND showtime.timeStart BETWEEN '$date $currentTime' and '$date 23:59:59' AND showtime.movieID = :id;", [
            "id" => $id
        ]);
        $showtimeList =  Showtime::query("SELECT showtime.* , cinema.cinemaID FROM cinema, room, showtime WHERE cinema.cinemaID = room.cinemaID AND room.roomID = showtime.roomID  and showtime.timeStart BETWEEN '$date $currentTime' and '$date 23:59:59' AND showtime.movieID =:id ;", [
            "id" => $id]);
     
        View::renderTemplate('detailMovie/show-time.html', [
            "id" =>$id,
            "listCinema" => $listCinema,
            "showtimeList" =>  $showtimeList,
        ]);
    
    }

    public function hasAuthority(): array
    {
        // TODO: Implement hasAuthority() method.
        return [];
    }
}

?>