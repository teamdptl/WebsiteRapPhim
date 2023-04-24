<?php 
namespace app\controller;
use core\Controller;
use core\View;
use app\model\Movie;
use app\model\Category;

class MoviesController extends Controller{
    public function getMoviesPage(){
        $navbar = GlobalController::getNavbar();
        $listMovie = Movie::findAll();
        $listCategory =Category::findAll();
        View::renderTemplate('movies\movies_page.html',[
            "navbar" => $navbar,
            "listMovie" => $listMovie,
            "listCategory" => $listCategory,
        ]);
    }
    
}


?>