<?php
namespace app\controller;
use app\model\Movie;
use app\model\Room;
use app\model\Showtime;
use core\Controller;
use core\Model;
use core\View;
use core\Request;
use app\model\User;
use app\utils\SessionManager;
use app\model\Movie;
use app\model\Category;
use app\model\MovieCategory;


class AdminQuanLyController extends Controller{

    public function getAdminQuanLyPhim() {

        // if (Request::$user != null){
        //     Request::redirect("/");
        //     return;
        // }


        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $listMovie = Movie::findAll(Model::ALL_OBJ);
        $listCategory = Category::findAll();

   
        
        View::renderTemplate('admin/managerFilm_page.html',[
            "navbar" => $navbar,
            "navAdmin"=> $navAdmin,
            "listMovie" => $listMovie,
            "listCategory" => $listCategory,
        ]);

    }

    public function AddMovie(){
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
    public function getAdminShowTimePage(){
        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        View::renderTemplate('admin/managerShowTime_page.html',[
            "navbar" => $navbar,
            "navAdmin"=> $navAdmin,
        ]);
    }
    public function getMovieName(){
        $id = $_GET["id"];
        $movie = Movie::find(Model::UN_DELETED_OBJ,$id);
        $json = json_encode($movie);
        echo $json;
        
    }
    public function getRoomName(){
        $id = $_GET["id"];
        $room = Room::query("SELECT room.roomName , cinema.* FROM cinema , room WHERE cinema.cinemaID = room.cinemaID and room.roomID = $id ;",);
        $json = json_encode($room);
        echo $json;
    }
    public function insertShowTime(){
        $movieID = $_POST["movieID"];
        $roomID = $_POST["roomID"];
        $duringtime = $_POST["duringtime"];
        $timeStart = $_POST["timeStart"];
        
        $showtime = new Showtime();
        $showtime->timeStart = $timeStart;
        $showtime->movieID = $movieID;
        $showtime->roomID =  $roomID;
        $showtime->duringTime = $duringtime;
        $showtime->isDeleted = "0";

        // $miliTime = strtotime($timeStart) + $duringtime*60000;
        $ShowTime =  strtotime($timeStart);
        $showtimecheck = Showtime::query("SELECT showtime.* FROM showtime WHERE showtime.roomID = '$roomID' ;");
        for( $i = 0 ; $i < count($showtimecheck); $i++)
        {
            $miliCheckStart = strtotime($showtimecheck[$i]->timeStart);
            $miliCheckEnd = strtotime($showtimecheck[$i]->timeStart) + $duringtime*60000;
            if( ($miliCheckStart >=  $ShowTime) && ( $ShowTime <=  $miliCheckEnd) ){
                
                
                $message["message"] = "Thời gian bị trùng";
                $message["status"] = "Thêm phim vào thời gian khác";
                $message["type"] = "error";
                $json =json_encode($message);
                echo $json;
                exit();   
            }
        }
        Showtime::save($showtime);
        $message =[]; 
        $message["message"] = "Thêm thành công";
        $message["status"] = "Dữ liệu đã được thêm vào cơ sở dữ liệu";
        $message["type"] = "success";
        $json =json_encode($message);
        echo $json;
                exit();  
        
    }
}