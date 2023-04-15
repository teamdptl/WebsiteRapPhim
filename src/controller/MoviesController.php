<?php 
namespace app\controller;
use core\Controller;
use core\View;
use app\model\MovieDetail;


class MoviesController extends Controller{
    public function getMoviesPage(){
        $navbar = GlobalController::getNavbar();
        $listMovie = MovieDetail::findAll();
     
        View::renderTemplate('movies\movies_page.html',[
            "navbar" => $navbar,
            "listMovie" => $listMovie,
        ]);
    }
    
}


?>