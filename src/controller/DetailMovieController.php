<?php
namespace app\controller;
use core\Controller;
use core\View;
use app\model\Movie;
use app\model\Room;
use app\model\Showtime;
use app\model\Cinema;
class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        $navbar = GlobalController::getNavbar();
        $movieDetail = Movie::find($id);
        $showTime = Showtime::where("showtime.movieId = $id");
        $cinema =  Cinema::where("(SELECT cinema.* FROM cinema INNER JOIN room ON room.cinemaID = cinema.cinemaID INNER JOIN showtime on showtime.roomID = room.roomID where cinema.cinemaID=$id)");
        View::renderTemplate('detailMovie/detail-movie.html', [
            "navbar" => $navbar,
            "movieDetail" => $movieDetail,
            "showTime" =>  $showTime,
            "cinema" =>$cinema
        ]);
    }
    
}

?>