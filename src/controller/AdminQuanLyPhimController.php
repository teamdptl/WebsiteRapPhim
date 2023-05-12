<?php
namespace app\controller;
use core\Controller;
use core\Model;
use core\View;
use core\Request;
use app\model\User;
use app\utils\SessionManager;
use app\model\Movie;
use app\model\Category;
use app\model\Tag;
use app\model\MovieCategory;


class AdminQuanLyPhimController extends Controller {

    public function getAdminQuanLyPhim() {

        // if (Request::$user != null){
        //     Request::redirect("/");
        //     return;
        // }


        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $listMovie = Movie::findAll(Model::ALL_OBJ);
        $listCategory = Category::findAll(Model::ALL_OBJ);
        $listTags = Tag::findAll(Model::ALL_OBJ);

   
        
        View::renderTemplate('admin/managerFilm_page.html',[
            "navbar" => $navbar,
            "navAdmin"=> $navAdmin,
            "listMovie" => $listMovie,
            "listCategory" => $listCategory,
            "listTags" => $listTags
        ]);

    }

    public function AddMovie(){
        $status = 0;
        $message = "";

        $nameMovie = $_POST["nameMovie"];
        $desMovie = $_POST["desMovie"]; 
        $trailerLink = $_POST["trailerLink"];
        $directors = $_POST["directors"];
        $actors = $_POST["actors"];
        $duringTime = $_POST["duringTime"];
        $datePicker = $_POST["datePicker"];
        $language = $_POST["language"];
        $customSwitches = $_POST["customSwitches"];
        $tagID =  $_POST["tagID"];
       
      
        if (isset($_POST["checkedIds"]) && !empty($_POST["checkedIds"])) {
            $checkedIds = explode(",", $_POST["checkedIds"]);
        }

        $nameMovieLength = strlen($nameMovie);
        $desMovieLength = strlen($desMovie);
        $trailerLinkLength = strlen($trailerLink);
        $directorsLength = strlen($directors);
        $actorsLength = strlen($actors);
        $languageLength = strlen($language);
        $duringTimeLength = strlen($duringTime);
        $datePickerLength = strlen($datePicker);

        

            $posterFileName = $_FILES["posterLink"]["name"];
            $landscapeFileName = $_FILES["landscapeLink"]["name"];

            $posterFileType = $_FILES["posterLink"]["type"];
            $landscapeFileType = $_FILES["landscapeLink"]["type"];

            $posterFileSize = $_FILES["posterLink"]["size"];
            $landscapeFileSize = $_FILES["landscapeLink"]["size"];

            $posterFileTmp = $_FILES["posterLink"]["tmp_name"];
            $landscapeFileTmp = $_FILES["landscapeLink"]["tmp_name"];
            
            $targetDirPoster = "assets/posterImgMovie/";
            $targetDirLandscape = "assets/landscapeImgMovie/";

         
            $maxFileSize = 5 * 1024 * 1024; // 5MB
           
            move_uploaded_file($posterFileTmp, $targetDirPoster . $posterFileName);
            move_uploaded_file($landscapeFileTmp, $targetDirLandscape . $landscapeFileName);
              


        if($nameMovieLength==0){
            $message = "Vui lòng nhập tên phim";
        }
        else if($desMovieLength==0){
            $message = "Vui lòng nhập mô tả phim";
        }
        else if(($posterFileType != "image/png" || $posterFileType != "image/jpeg" || $posterFileType != "image/jpg") && $posterFileSize > $maxFileSize){
            unlink($targetDirPoster . $posterFileName);
            $message = "Vui lòng thêm ảnh poster";
        }
        else if(($landscapeFileType != "image/png" || $landscapeFileType != "image/jpeg" || $landscapeFileType != "image/jpg") && $landscapeFileSize > $maxFileSize){
            unlink($targetDirLandscape . $landscapeFileName);
            $message = "Vui lòng thêm ảnh landscape";
        }
        else if($trailerLinkLength==0){
            $message = "Vui lòng nhập link trailer";
        }
        else if($directorsLength==0){
            $message = "Vui lòng nhập tên đạo diễn";
        }
        else if($actorsLength==0){
            $message = "Vui lòng nhập tên diễn viên";
        }
        else if($duringTimeLength==0){
            $message = "Vui lòng nhập thời gian diễn ra";
        }
        else if($datePickerLength==0){
            $message = "Vui lòng nhập ngày chiếu";
        }
        else if($languageLength==0){
            $message = "Vui lòng nhập ngôn ngữ";
        }else{
            $movie = new Movie();
            $movie -> movieName = $nameMovie;
            $movie -> movieDes = $desMovie;
            $movie -> posterLink = $posterFileName;
            $movie -> landscapePoster = $landscapeFileName;
            $movie -> trailerLink = $trailerLink;
            $movie -> movieDirectors = $directors;
            $movie -> movieActors = $actors;
            $movie -> duringTime = $duringTime;
            $movie -> dateRelease = $datePicker." 00:00:00";
            $movie -> movieLanguage = $language;
            $movie -> isFeatured = $customSwitches;
            $movie -> tagID =$tagID; 

            $movieID = Movie::save($movie);

            $movieCategory = new MovieCategory();

            foreach ($checkedIds as $checkedId) {
                $movieCategory ->movieID =  $movieID;
                $movieCategory ->categoryID = intval($checkedId);

                MovieCategory::save($movieCategory);

                $status = 1;
                $message = "Thêm phim thành công";

            }

           
        }
        
        $this->jsonAdminQuanLyPhim($status, $message);
    }

    public function jsonAdminQuanLyPhim($status = 0, $message = ""){
        echo json_encode([
            "status" => $status,
            "message" => $message
        ]);
    }

}   