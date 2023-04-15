<?php
namespace app\controller;
use core\Controller;
use core\View;
use app\model\MovieDetail;

class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        $navbar = GlobalController::getNavbar();
        $movieDetail = MovieDetail::find($id);
        View::renderTemplate('detailMovie/detail-movie.html', [
            "navbar" => $navbar,
            "movieDetail" => $movieDetail,
        ]);
    }
    
}

?>