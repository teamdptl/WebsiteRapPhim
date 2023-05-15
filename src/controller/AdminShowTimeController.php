<?php 
namespace app\controller;
use app\controller\GlobalController;
use app\model\Movie;
use app\model\Room;
use core\Controller;
use app\model\MovieCategory;
use app\model\Cinema;
use app\model\Showtime;
use core\Model;
use core\View;

class AdminShowTimeController extends Controller{
    public function getAdminShowTimePage()
    {
        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $showTime = Showtime::findAll();
        $movie = Movie::findAll();
        $cinema = Cinema::query("SELECT room.roomID ,room.roomName, cinema.* FROM room , cinema WHERE room.cinemaID = cinema.cinemaID");
        View::renderTemplate('admin/managerShowTime_page.html', [
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
            "showTimeList" => $showTime,
            "movieList" => $movie,
            "cinemaList" => $cinema,
        ]);
    }
    public function getMovieName()
    {
        $id = $_GET["id"];
        $movie = Movie::find(Model::UN_DELETED_OBJ, $id);
        $json = json_encode($movie);
        echo $json;
    }
    public function getRoomName()
    {
        $id = $_GET["id"];
        $room = Room::query("SELECT room.roomName , cinema.* FROM cinema , room WHERE cinema.cinemaID = room.cinemaID and room.roomID = $id ;", );
        $json = json_encode($room);
        echo $json;
    }
    public function insertShowTime()
    {
        $movieID = $_POST["movieID"];
        $roomID = $_POST["roomID"];
        $duringtime = $_POST["duringtime"];
        $timeStart = $_POST["timeStart"];
        $showtime = new Showtime();
        $showtime->timeStart = $timeStart;
        $showtime->movieID = $movieID;
        $showtime->roomID = $roomID;
        $showtime->duringTime = $duringtime;
        $showtime->isDeleted = "0";
        $ShowTimeStart = date("Y-m-d H:i:s", strtotime($timeStart));
        $ShowTimeEnd = date("Y-m-d H:i:s", strtotime("$timeStart + $duringtime minutes"));
        $showtimecheck = Showtime::query("SELECT showtime.* FROM showtime WHERE showtime.roomID = '$roomID' ;");
        for ($i = 0; $i < count($showtimecheck); $i++) {
            $timeStartCheck = $showtimecheck[$i]->timeStart;
            $duringtimeCheck = $showtimecheck[$i]->duringTime;
            $miliCheckStart = date("Y-m-d H:i:s", strtotime($timeStartCheck));
            $miliCheckEnd = date("Y-m-d H:i:s", strtotime("$timeStartCheck + $duringtimeCheck minutes"));
            if (($ShowTimeStart >= $miliCheckStart) && ($ShowTimeStart <= $miliCheckEnd) || ($ShowTimeEnd >= $miliCheckStart) && ($ShowTimeEnd <= $miliCheckEnd)) {
                $message["message"] = "Thời gian bị trùng";
                $message["status"] = "Thêm phim vào thời gian khác";
                $message["type"] = "error";
                $json = json_encode($message);
                echo $json;
                exit();
            }
        }
        Showtime::save($showtime);
        $message = [];
        $message["message"] = "Thêm thành công";
        $message["status"] = "Dữ liệu đã được thêm vào cơ sở dữ liệu";
        $message["type"] = "success";
        $json = json_encode($message);
        echo $json;
        exit();

    }
    public function editShowTime()
    {
        $showID = $_GET["showID"];
        $showtime = ShowTime::query("SELECT showtime.showID ,room.roomName , cinema.cinemaName , movie.movieName , movie.movieID , room.roomID,showtime.timeStart,showtime.duringTime from room , cinema , showtime ,movie WHERE room.cinemaID = cinema.cinemaID and showtime.roomID  = room.roomID AND movie.movieID = showtime.movieID AND showtime.showID =$showID");
        $json = json_encode($showtime);
        echo $json;
    }
    public function updateShowTime()
    {
        $movieID = $_POST["movieID"];
        $roomID = $_POST["roomID"];
        $duringtime = $_POST["duringtime"];
        $timeStart = $_POST["timeStart"];
        $showID = $_POST["showID"];
        $showtime = new Showtime();
        $showtime->showID = $showID;
        $showtime->timeStart = $timeStart;
        $showtime->movieID = $movieID;
        $showtime->roomID = $roomID;
        $showtime->duringTime = $duringtime;
        $showtime->isDeleted = "0";
        $ShowTimeStart = date("Y-m-d H:i:s", strtotime($timeStart));
        $ShowTimeEnd = date("Y-m-d H:i:s", strtotime("$timeStart + $duringtime minutes"));
        $showtimecheck = Showtime::query("SELECT showtime.* FROM showtime WHERE showtime.roomID = '$roomID' and showtime.showID != $showID ;");
        for ($i = 0; $i < count($showtimecheck); $i++) {
            $timeStartCheck = $showtimecheck[$i]->timeStart;
            $duringtimeCheck = $showtimecheck[$i]->duringTime;
            $miliCheckStart = date("Y-m-d H:i:s", strtotime($timeStartCheck));
            $miliCheckEnd = date("Y-m-d H:i:s", strtotime("$timeStartCheck + $duringtimeCheck minutes"));
            // var_dump( $miliCheckEnd);
            if (($ShowTimeStart >= $miliCheckStart) && ($ShowTimeStart <= $miliCheckEnd) || ($ShowTimeEnd >= $miliCheckStart) && ($ShowTimeEnd <= $miliCheckEnd)) {
                $message["message"] = "Thời gian bị trùng";
                $message["status"] = "Sửa phim vào thời gian khác";
                $message["type"] = "error";
                $json = json_encode($message);
                echo $json;
                exit();
            }
        }
            Showtime::update($showtime,$showID);
            $message = [];
            $message["message"] = "Sửa thành công";
            $message["status"] = "Dữ liệu đã được sử trong cơ sở dữ liệu";
            $message["type"] = "success";
            $json = json_encode($message);
            echo $json;
            exit();
        }
    }


?>