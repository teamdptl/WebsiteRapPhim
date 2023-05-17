<?php
namespace app\controller;
use core\Controller;
use core\Request;
use core\Model;
use core\View;
use app\model\Showtime;
use app\model\ShowtimePrice;
use app\model\SeatShowtime;
use app\model\Movie;
use app\model\Seat;
use app\model\Room;
use app\model\Cinema;
use app\model\Province;
use app\model\Food;
use app\model\FoodBooking;
use app\model\Booking;

class BookingController extends Controller{


    public function getBookingPage(){

        //Check if user == null
        if(Request::$user == null){
            Request::redirect('/signin');
            return ;
        }

        $navbar = GlobalController::getNavbar();

        $showID = $_GET['showID'];
        $g_showtime = Showtime::find(Model::UN_DELETED_OBJ, $showID);
        
        //Check if showtime != null
        if($g_showtime != null){

            $movie = Movie::find(Model::UN_DELETED_OBJ, $g_showtime->movieID);
            $room = Room::find(Model::UN_DELETED_OBJ, $g_showtime->roomID);

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
                        $startTime = date("h:i d/m/Y", strtotime($g_showtime->timeStart));
                        $endTime = date("h:i d/m/Y", strtotime($g_showtime->timeStart ."+" .$g_showtime->duringTime ."minutes"));
                        $listFood = self::handleFoodArrayObj(Food::findAll(Model::UN_DELETED_OBJ));
                        View::renderTemplate('/booking/bookingSeat_page.html', [
                            "listSeat" => $listSeatArr,
                            "showtime" => get_object_vars($g_showtime),
                            "startTime" => $startTime,
                            "endTime" => $endTime,
                            "movie" => get_object_vars($movie),
                            "cinema" => $cinema,
                            "roomName" => $roomName,
                            "provinceName" => $provinceName,
                            "listFood" => $listFood,
                            "foodLength" => count($listFood),
                            "navbar" => $navbar
                        ]);

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

    public function handleFoodArrayObj($listFood){

        $arrayFoodJSON = array();

        foreach ($listFood as $food) {
            array_push($arrayFoodJSON, get_object_vars($food));
        }
        return $arrayFoodJSON;
    }

    public function isAbleSeats(){
        $listSeatID = $_POST['listRealCheckedID'];
        $showID = $_POST['showID'];
        $listBookedID = array();

        //Check login
        //Check if user == null
        if(Request::$user == null){
            Request::redirect('/signin');
            echo json_encode(array('announcement' => 'login'));
            return ;
        }

        if(!self::isValidSeatID($listSeatID)){
            echo json_encode(array('announcement' => 'Something went wrong! Please refresh page'));
            return;
        }

        if(count($listSeatID) > 0){
            foreach ($listSeatID as $seatID) {
                $seatShowtime = SeatShowtime::find(Model::UN_DELETED_OBJ, (int)$showID, (int)$seatID);
                if($seatShowtime != null && $seatShowtime->isBooked ){
                    array_push($listBookedID, $seatShowtime->seatID);            
                }
            }
            if(count($listBookedID) > 0){
                echo json_encode(array('announcement' => 'booked', 'listBookedID' => $listBookedID));
            }else echo json_encode(array('announcement' => 'success'));
        }else{
            echo json_encode(array('announcement' => 'Please choose your seat first'));
        }
    }

    public function isValidSeatID($lsSeatID){
        $seat = null;
        $g_listSeat = array();
        foreach($lsSeatID as $seatID){
            $seat = Seat::find(Model::UN_DELETED_OBJ, (int)$seatID);
            if( $seat != null){
                array_push($g_listSeat, $seat);
            }else{
                array_splice($g_listSeat, 0, count($g_listSeat));
                return false;
            }
        }
        return $g_listSeat;
    }

    public function handleInfoForPaymentPage(){
        $listFoodJSON = $_POST['listFood'] ?? [];
        $listSeatJSON = $_POST['listRealCheckedID'] ?? [];
        $showID = $_POST['showID'] ?? 0;
        $status = 1;
        $message = 'Something went wrong!';
        $foodPrice = 0;
        $ticketPrice = 0;
        $totalPrice = 0;
        $foodBooked = '';
        $STDlist = '';

        $g_showtime = Showtime::find(Model::UN_DELETED_OBJ, $showID);
        if($g_showtime == null){
            $status = 0;
        }else{
            $startTime = date("h:i d/m/Y", strtotime($g_showtime->timeStart));
            $endTime = date("h:i d/m/Y", strtotime($g_showtime->timeStart ."+" .$g_showtime->duringTime ."minutes"));
        }
        

        if(Request::$user == null){
            $status = 0;
        }

        $handleFood = self::handleFoodBooked($listFoodJSON);
        if($handleFood == 0){
            $status = 0;
        }else{
            $foodPrice = $handleFood['foodPrice'];
            $foodBooked = $handleFood['listFoodName'];
        }

        if(self::isValidSeatID($listSeatJSON) == false){
            $status = 0;
        }else $handleSeat = self::handleSeatNameBooked(self::isValidSeatID($listSeatJSON), $g_showtime->showID);

        if($handleSeat == 0){
            $status = 0;
        }else{
            $ticketPrice = $handleSeat['ticketPrice'];
            $STDlist = $handleSeat['listSeatName'];
        }

        $totalPrice = $ticketPrice + $foodPrice;

        self::jsonBookingRespone($status, $message, $foodPrice, $ticketPrice , $totalPrice , $foodBooked, $STDlist, $startTime, $endTime);
    }

    public function handleFoodBooked($listFoodJSON){
        $listFoodName = '';
        $foodPrice = 0;
        foreach($listFoodJSON as $food){
            $foodObj = Food::find(Model::UN_DELETED_OBJ, $food['foodID']);
            if($foodObj != null){
                $foodPrice += $foodObj->foodPrice * $food['quantity'];
                $listFoodName .= $foodObj->foodName ." x"  .$food['quantity'] .", " ;
            } else{
                return 0;
            } 
        }
        $g_listFood = $listFoodJSON;
        $listFoodName = rtrim($listFoodName, ', ');
        return ["listFoodName" => $listFoodName, "foodPrice" => $foodPrice];
    }

    public function handleSeatNameBooked($g_listSeat, $showID){
        $listSeatName = '';
        $seatPrice = 0;

        foreach($g_listSeat as $seat){
            $listSeatName .= $seat->seatRow .$seat->seatCol .", ";
            $showtimePrice = ShowtimePrice::find(Model::UN_DELETED_OBJ, $showID, $seat->seatType);
            if($showtimePrice != null){
                $seatPrice += $showtimePrice->ticketPrice;
            }else return 0;
        }

        $listSeatName = rtrim($listSeatName, ', ');
        return ["listSeatName" => $listSeatName, "ticketPrice" => $seatPrice];
    }

    public function finalPayment(){
        $listFoodJSON = $_POST['listFood'] ?? [];
        $listSeatJSON = $_POST['listRealCheckedID'] ?? [];
        $showID = $_POST['showID'] ?? 1;
        $methodPay = $_POST['methodPay'] ?? "";
        $message = '';
        $status = 1;
        if(Request::$user == null){
            $message = 'Your section time out! Login please';
            $status = 0;
        }else{
            $userID = Request::$user->userID;
            //Tạo hóa đơn
            $booking = new Booking();
            $booking->bookingID = 0;
            $booking->bookName = '';
            $booking->bookEmail = Request::$user->email;     
            $booking->bookTime = date('Y-m-d H:i:s');
            $booking->methodPay = $methodPay;
            $booking->isPaid = false;
            $booking->userID = $userID;
            $booking->isDeleted = false;
            $bookingID = Booking::save($booking);
        
            if($bookingID == -1){
                $message = 'Something went wrong!';
                $status = 0;
            }else{
                //Kiểm tra xem danh sách ID ghế truyền vào có hợp lệ không
                $g_listSeat = self::isValidSeatID($listSeatJSON);
                if($g_listSeat == 0){
                    $status = 0;
                    $message = 'Something went wrong!';
                }else{

                    //Thêm chi tiết vé
                    foreach($g_listSeat as $seat){
                        //Kiểm tra xem ghế đã tồn tại chưa
                        $seatShowtime = SeatShowtime::find(Model::UN_DELETED_OBJ, $showID, $seat->seatID);
                        if($seatShowtime != null){
                            if($seatShowtime->isBooked){
                                $status = 0;
                                $message = 'Your seat was booked';
                            }else{
                                $seatShowtime->pickedAt = date('Y-m-d H:i:s');
                                $seatShowtime->isBooked = true;
                                SeatShowtime::update($seatShowtime, $seatShowtime->showID, $seatShowtime->seatID);
                            }
                            
                        }else{
                            $showtimePrice = ShowtimePrice::find(Model::UN_DELETED_OBJ, $showID, $seat->seatType);
                            $seatShowtime = new SeatShowtime();
                            $seatShowtime->showID = $showID;
                            $seatShowtime->seatID = $seat->seatID;
                            $seatShowtime->pickedAt = date('Y-m-d H:i:s');
                            $seatShowtime->seatPrice = $showtimePrice->ticketPrice;
                            $seatShowtime->isBooked = true;
                            $seatShowtime->userID = $userID;
                            $seatShowtime->bookingID = $bookingID;
                            SeatShowtime::save($seatShowtime);
                        }
                    }

                }

                //Thêm chi tiết đồ ăn
                foreach($listFoodJSON as $food){
                    $foodObj = Food::find(Model::UN_DELETED_OBJ, $food['foodID']);
                    if($foodObj != null){
                        $foodBooking = new FoodBooking();
                        $foodBooking->bookingID = $bookingID;
                        $foodBooking->foodID = $foodObj->foodID;
                        $foodBooking->foodPrice = $foodObj->foodPrice;
                        $foodBooking->foodUnit = $food['quantity'];
                        FoodBooking::save($foodBooking);
                    } else{
                        $status = 0;
                        $message = 'Something went wrong!';
                    } 
                }
            }
        }

        echo json_encode(["status" => $status, "message" => $message]);
    }

    public function jsonBookingRespone($status = 0, $message = "", $foodPrice = 0, $ticketPrice = 0, $totalPrice = 0, $foodBooked = '', $STDlist = '', $startTime = '', $endTime = ''){
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "foodPrice" => $foodPrice,
            "ticketPrice" => $ticketPrice,
            "totalPrice" => $totalPrice,
            "foodBooked" => $foodBooked,
            "STDlist" => $STDlist,
            "startTime" => $startTime,
            "endTime" => $endTime
        ]);
    }

}