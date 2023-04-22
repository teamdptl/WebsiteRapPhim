<?php
namespace app\controller;
use core\Controller;
use core\View;
use app\model\Movie;

class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        $navbar = GlobalController::getNavbar();
        $movieDetail = Movie::find($id);
        View::renderTemplate('detailMovie/detail-movie.html', [
            "navbar" => $navbar,
            "movieDetail" => $movieDetail,
        ]);
    }
    
}

?>