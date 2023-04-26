<?php 
namespace app\controller;
use core\Controller;
use core\View;
use app\model\Movie;
use app\model\Category;
use core\Model;

class MoviesController extends Controller{
    public function getMoviesPage(){
        $navbar = GlobalController::getNavbar();
        $listMovie = Movie::findAll(Model::ALL_OBJ);
        // $listCategory =Category::findAll(Model::ALL_OBJ);
        View::renderTemplate('movies\movies_page.html',[
            "navbar" => $navbar,
            "listMovie" => $listMovie,
            // "listCategory" => $listCategory,
        ]);
    }
    
}


?>