<?php
namespace app\controller;
use core\Controller;
use core\Request;
use core\Model;
use app\model\Showtime;
use app\model\ShowtimePrice;
use app\model\SeatShowtime;
use app\model\Movie;
use app\model\Seat;
use app\model\Room;
use app\model\Cinema;
use app\model\Province;


class BookingController extends Controller{


    public function getBookingPage(){

        //Check if user == null
        if(Request::$user == null){
            Request::redirect('/signin');
            return;
        }

        // $showID = $_POST['showID'];
        $showID = 1;
        $showtime = Showtime::find(Model::UN_DELETED_OBJ, $showID);
        
        //Check if showtime != null
        if($showtime != null){

            $movie = Movie::find(Model::UN_DELETED_OBJ, $showtime->movieID);
            $room = Room::find(Model::UN_DELETED_OBJ, $showtime->roomID);

            //Check if movie and room != null
            if($movie != null && $room != null){
                $cinema = Cinema::find(Model::UN_DELETED_OBJ, $room->cinemaID);
                $roomID = $room->roomID;
                $listSeatObj = Seat::where("roomID = :roomID", compact("roomID"));
                
                if($listSeatObj != null && $cinema != null){   
                    $province = Province::find(Model::UN_DELETED_OBJ, $cinema->provinceID);
                    if($province != null){
                        $listSeatArr = self::handleBoxSeat($listSeatObj, $room->numberOfRow, $room->numberOfCol, $showID);
                        $cinema = get_object_vars($cinema);
                        $roomName = $room->roomName;
                        $provinceName = $province->provinceName;
                        echo json_encode($listSeatArr);
                        // View::renderTemplate('/booking/bookingSeat_page.html', [
                        //     "listSeat" => $listSeatArr,
                        //     "cinema" => $cinema,
                        //     "roomName" => $roomName,
                        //     "provinceName" => $provinceName
                        // ]);

                    }         
                    
                }
            }
        }

    }


    public function handleBoxSeat($listSeatObj, $numOfRow, $numOfCol, $showID){

        $listBoxSeatArr = array();
        $listRowSeatArr = array();
        $seatArr = array();
        $col = 1;
        $row = 1;
        // echo "number of row $numOfRow";
        // echo "number of column $numOfCol </br>";

        foreach ($listSeatObj as $seat) {

            if($col%$numOfCol == 1){

                if($col > 1){
                    $listBoxSeatArr["$row"] = $listRowSeatArr;
                    $listRowSeatArr = array();
                    $row++;
                    // echo "</br>";
                }
                // echo "row $row";                
                
            }

            // echo "column $col";
            //Lấy giá tiền và trạng thái của ghế
            $showtimePrice = ShowtimePrice::find(Model::UN_DELETED_OBJ, $showID, $seat->seatType);
            $seatShowtime = SeatShowtime::find(Model::UN_DELETED_OBJ, $showID, $seat->seatID);
            if($showtimePrice == null) return null;

            if($seatShowtime != null){
                $seatArr = array_merge(get_object_vars($seat), ["price" => $showtimePrice->ticketPrice], ["isBooked" => $seatShowtime->isBooked]);
            }else{
                $seatArr = array_merge(get_object_vars($seat), ["price" => $showtimePrice->ticketPrice], ["isBooked" => false]);
            }

            array_push($listRowSeatArr, $seatArr);

            if($col == count($listSeatObj)){
                $listBoxSeatArr["$row"] = $listRowSeatArr;
            }

            $col++;
        }

        return $listBoxSeatArr;
    }



}