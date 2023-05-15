<?php
namespace app\controller;

use app\model\Cinema;
use app\model\Movie;
use app\model\Room;
use app\model\Showtime;
use core\Controller;
use core\Model;
use core\View;
use core\Request;
use app\model\User;
use app\utils\SessionManager;
// use app\model\Movie;
use app\model\Category;
use app\model\MovieCategory;


class AdminQuanLyController extends Controller
{

    public function getAdminQuanLyPhim()
    {

        // if (Request::$user != null){
        //     Request::redirect("/");
        //     return;
        // }


        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $listMovie = Movie::findAll(Model::ALL_OBJ);
        $listCategory = Category::findAll();



        View::renderTemplate('admin/managerFilm_page.html', [
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
            "listMovie" => $listMovie,
            "listCategory" => $listCategory,
        ]);

    }

    public function AddMovie()
    {
        $nameMovie = $_POST["nameMovie"];
        $desMovie = $_POST["desMovie"];
        $trailerLink = $_POST["trailerLink"];
        $directors = $_POST["directors"];
        $actors = $_POST["actors"];
        $duringTime = $_POST["duringTime"];
        $datePicker = $_POST["datePicker"];
        $language = $_POST["language"];
        $customSwitches = $_POST["customSwitches"];


        $posterFileName = $_FILES["posterLink"]["name"];

        $landscapeFileName = $_FILES["landscapeLink"]["name"];



        $targetDirPoster = "assets/posterImgMovie/";
        $targetDirLandscape = "assets/landscapeImgMovie/";

        move_uploaded_file($_FILES["posterLink"]["tmp_name"], $targetDirPoster . $posterFileName);
        move_uploaded_file($_FILES["landscapeLink"]["tmp_name"], $targetDirLandscape . $landscapeFileName);


    }
}
