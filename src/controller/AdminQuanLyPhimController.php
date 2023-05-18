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
        $listMovie = Movie::findAll();
        $listCategory = Category::findAll();
        $listTags = Tag::findAll();

        // $oneMovie = Movie::find(1,  $movieID );

        $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : 0;
        $searchValue = isset($_GET['searchValue']) ? $_GET['searchValue'] : '';

           

        if ($searchType === '0') {
            // Tìm kiếm theo ID
            $listMovie = Movie::query("SELECT * FROM movie WHERE movie.movieName   LIKE '%$searchValue%' AND movie.isDeleted = 0");
        } elseif ($searchType === '1') {
            // Tìm kiếm theo thể loại
            $listMovie = Movie::query("SELECT * FROM movie
                                    INNER JOIN movie_category ON movie.movieID = movie_category.movieID
                                    INNER JOIN category ON movie_category.categoryID = category.categoryID
                                    WHERE category.cateName = '$searchValue' AND movie.isDeleted = 0");
        } elseif ($searchType === '2') {
            // Tìm kiếm theo độ tuổi
            $listMovie = Movie::query("SELECT * FROM movie
                                    INNER JOIN tag ON movie.tagID = tag.tagID
                                    WHERE tag.minAge = $searchValue AND movie.isDeleted = 0");
        }

        // Số lượng phim hiển thị trên mỗi trang
        $itemsPerPage = 6;  
        $totalItems = count($listMovie);
        $totalPages = ceil($totalItems / $itemsPerPage);

        $currentPage = $_GET['page'] ?? 1;
        // $currentPage = max(1, min($currentPage, $totalPages));

        // Tính toán offset (vị trí bắt đầu của trang hiện tại)
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Lấy danh sách phim cho trang hiện tại
        $paginatedMovies = array_slice($listMovie, $offset, $itemsPerPage);
        //Lấy danh sách navigation
        $navigationRange = range(max(1, $currentPage - 1), min($currentPage + 2, $totalPages));

        
        
        View::renderTemplate('admin/managerFilm_page.html',[
            "navbar" => $navbar,
            "navAdmin"=> $navAdmin,
            "listMovie" => $listMovie,
            "listCategory" => $listCategory,
            "listTags" => $listTags,
            "paginatedMovies" => $paginatedMovies,
            "currentPage" => $currentPage,
            "totalPages" => $totalPages,
            "navigationRange" => $navigationRange,
            "searchType" => $searchType,
            "searchValue" => $searchValue,

        ]);

    }

   

    public function getOneMovie(){
        if(isset( $_GET["movieID"]) && !empty($_GET["movieID"])){
            $movieID =  $_GET["movieID"];
            $movie = Movie::query("SELECT * FROM movie WHERE movie.movieID = $movieID");
            $tagID =  $movie[0]->tagID;
            $minAge = Tag::query("SELECT tag.minAge FROM tag WHERE tag.tagID = $tagID");
                
            $listCategory = MovieCategory::query("SELECT movie_category.categoryID FROM movie_category WHERE movie_category.movieID = $movieID");
            $posterLink = $movie[0]->posterLink;
            $landscapePoster =  $movie[0]->landscapePoster;

    
            } else {
                echo "Missing movieID parameter";
            }
        
    
            echo json_encode([
                "movie" => $movie,
                "minAge" => $minAge,
                "listCategory" => $listCategory,
                "posterLink" => $posterLink,
                "landscapePoster" => $landscapePoster,
                
            ]);
            
    }


    public function AddMovie(){
        $status = 0;
        $message = "Thành công";

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



        
        $posterFileName = $_FILES["posterLink"]["name"] ?? "" ;
        $landscapeFileName = $_FILES["landscapeLink"]["name"] ?? "";
        
        $posterFileType = $_FILES["posterLink"]["type"] ?? "";
        $landscapeFileType = $_FILES["landscapeLink"]["type"] ?? "";

        
        $posterFileSize = $_FILES["posterLink"]["size"] ?? "";
        $landscapeFileSize = $_FILES["landscapeLink"]["size"] ?? "";
        
        $posterFileTmp = $_FILES["posterLink"]["tmp_name"] ?? "";
        $landscapeFileTmp = $_FILES["landscapeLink"]["tmp_name"] ?? "";
        
        $targetDirPoster = "assets/posterImgMovie/";
        $targetDirLandscape = "assets/landscapeImgMovie/";

               
   
        
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        if ($nameMovieLength == 0) {
          $message = "Vui lòng nhập tên phim";
        } else if ($desMovieLength == 0) {
          $message = "Vui lòng nhập mô tả phim";
        } else if ($posterFileName == "") {
            $message = "Vui lòng thêm ảnh poster";
        } else if ($landscapeFileName == "") {
            $message = "Vui lòng thêm ảnh landscape";
        }
        else if (!in_array($posterFileType, array("image/png", "image/jpeg", "image/jpg")) || $posterFileSize > $maxFileSize ||
                 !in_array($landscapeFileType, array("image/png", "image/jpeg", "image/jpg")) || $landscapeFileSize > $maxFileSize) {
          if (file_exists($targetDirPoster . $posterFileName)) {
            unlink($targetDirPoster . $posterFileName);
          }
          if (file_exists($targetDirLandscape . $landscapeFileName)) {
            unlink($targetDirLandscape . $landscapeFileName);
          }
          $message = "Vui lòng thêm ảnh poster và landscape đúng định dạng và dung lượng tối đa là 5MB";
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
        }
        else if(empty($checkedIds)){
            $message = "Vui lòng thêm ít nhất 1 thể loại";
        }
        else{

            move_uploaded_file($posterFileTmp, $targetDirPoster . $posterFileName);
            move_uploaded_file($landscapeFileTmp, $targetDirLandscape . $landscapeFileName);


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

    public function DeleteMovie(){
        if(isset($_POST["movieID"])){
            $movieID = $_POST["movieID"];
            echo $movieID;
        } else {
            echo "Missing movieID parameter";
        }

         Movie::delete(true,$movieID);


    }

    public function UpdateMovie(){
        $status = 0;
        $message = "Thành công";

        $movieID = $_POST["movieID"];
        $nameMovie = $_POST["nameMovie"];
        $desMovie = $_POST["desMovie"]; 
        $trailerLink = $_POST["trailerLink"];
        $directors = $_POST["directors"];
        $actors = $_POST["actors"];
        $duringTime = $_POST["duringTime"];
        $datePicker = $_POST["datePicker"];
        $language = $_POST["language"];
        $customSwitches = $_POST["customSwitches"];
        // $tagID =  $_POST["tagID"];
        $minAge = $_POST["minAge"];
        $tagIdQuery = Tag::query("SELECT tag.tagID FROM tag WHERE tag.minAge = $minAge");

        $tagIdDone = $tagIdQuery[0]->tagID;

        // echo $tagIdDone;
           //     $posterFileName = $movieFile[0]->posterLink;
        // $landscapeFileName = $movieFile[0]->landscapePoster;


      
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



        
        $posterFileName = $_FILES["posterLink"]["name"] ?? "" ;
        $landscapeFileName = $_FILES["landscapeLink"]["name"] ?? "";
        
        $posterFileType = $_FILES["posterLink"]["type"] ?? "";
        $landscapeFileType = $_FILES["landscapeLink"]["type"] ?? "";

        
        $posterFileSize = $_FILES["posterLink"]["size"] ?? "";
        $landscapeFileSize = $_FILES["landscapeLink"]["size"] ?? "";
        
        $posterFileTmp = $_FILES["posterLink"]["tmp_name"] ?? "";
        $landscapeFileTmp = $_FILES["landscapeLink"]["tmp_name"] ?? "";
        
        $targetDirPoster = "assets/posterImgMovie/";
        $targetDirLandscape = "assets/landscapeImgMovie/";

        $movieFile = Movie::where("movieID = :movieID", compact('movieID'));

        if($posterFileName == ""){
            $posterFileName = $movieFile[0]->posterLink;
        }

        if($landscapeFileName == ""){
            $landscapeFileName = $movieFile[0]->landscapePoster;
        }
        
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        if ($nameMovieLength == 0) {
          $message = "Vui lòng nhập tên phim";
        } else if ($desMovieLength == 0) {
          $message = "Vui lòng nhập mô tả phim";
        } else if ($posterFileName == "") {
            $message = "Vui lòng thêm ảnh poster";
        } else if ($landscapeFileName == "") {
            $message = "Vui lòng thêm ảnh landscape";
        }
        // else if (!in_array($posterFileType, array("image/png", "image/jpeg", "image/jpg")) || $posterFileSize > $maxFileSize ||
        //          !in_array($landscapeFileType, array("image/png", "image/jpeg", "image/jpg")) || $landscapeFileSize > $maxFileSize) {
        //   if (file_exists($targetDirPoster . $posterFileName)) {
        //     unlink($targetDirPoster . $posterFileName);
        //   }
        //   if (file_exists($targetDirLandscape . $landscapeFileName)) {
        //     unlink($targetDirLandscape . $landscapeFileName);
        //   }
        //   $message = "Vui lòng thêm ảnh poster và landscape đúng định dạng và dung lượng tối đa là 5MB";
        // }
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
        }
        else if(empty($checkedIds)){
            $message = "Vui lòng thêm ít nhất 1 thể loại";
        }
        else{

            move_uploaded_file($posterFileTmp, $targetDirPoster . $posterFileName);
            move_uploaded_file($landscapeFileTmp, $targetDirLandscape . $landscapeFileName);


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
            $movie -> tagID =$tagIdDone; 

             Movie::update($movie, $movieID);
            
            // $status = 1;
            // $message = "Sửa phim thành công";


            // $movieCategory = new MovieCategory();

            $currentCategoryIDs = MovieCategory::query("SELECT categoryID FROM movie_category WHERE movieID = $movieID");

            $currentCategoryIDsArray = [];
            foreach ($currentCategoryIDs as $category) {
                $currentCategoryIDsArray[] = $category->categoryID;
            }
            // echo $currentCategoryIDs;

            $addCategoryIDs = array_diff($checkedIds, $currentCategoryIDsArray);
            $deleteCategoryIDs = array_diff($currentCategoryIDsArray, $checkedIds);

            foreach ($addCategoryIDs as $categoryID) {
                $movieCategory = new MovieCategory();
                $movieCategory->movieID = $movieID;
                $movieCategory->categoryID = intval($categoryID);
                MovieCategory::save($movieCategory);
            }
            
            // // Xóa các categoryID cũ:
            foreach ($deleteCategoryIDs as $categoryID) {
                MovieCategory::delete(true, $categoryID);
            }

            
            $status = 1;
            $message = "Sửa phim thành công";


            // foreach ($checkedIds as $checkedId) {
            //     $movieCategory ->movieID =  $movieID;
            //     $movieCategory ->categoryID = intval($checkedId);


               

                // MovieCategory::update($movieCategory, $movieID);


            // }

           
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