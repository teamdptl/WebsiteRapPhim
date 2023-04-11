<?php 
namespace app\controller;
use core\Controller;
use core\View;

class DetailMovieController extends Controller {
    public function getDetailMoviePage($name){
        $navbar = GlobalController::getNavbar();

       View::renderTemplate('detailMovie/detail-movie.html',[
               "navbar" => $navbar,
               "name" =>$name,
       ]); 
    }
}
?>