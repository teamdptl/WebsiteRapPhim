<?php

namespace app\controller;

use core\Request;
use core\View;
use core\Model;
use app\model\Booking;
use app\model\Cinema;
use app\model\Room;
use app\model\Showtime;
use app\model\Movie;
use app\model\SeatShowtime;
use app\model\Seat;
use app\model\Food;
use app\model\FoodBooking;


class UserProfileController
{
    public function getProfilePage(){
        $navBar = GlobalController::getNavbar();
        $listBookingArr = [];
        
        if(Request::$user == null){
            Request::redirect('/signin');
        }else{
            $userID = Request::$user->userID;
            $bookingList = Booking::where("userID = :userID", ["userID" => $userID]) ?? [];
            foreach($bookingList as $booking){
                // echo $booking->isPaid;
                // echo json_encode(self::getInfoRelateToBooking($booking->bookingID, $booking->isPaid));
                array_push($listBookingArr, self::getInfoRelateToBooking($booking->bookingID, $booking->isPaid));
            }
        }


        View::renderTemplate("profile/userProfileOrder.html", [
            "navbar" => $navBar,
            "orderPage" => true,
            "user" => get_object_vars(Request::$user),
            "createAt" => date('d/m/Y', strtotime(Request::$user->createAt)),
            "firstName" => self::getFirstName(Request::$user->fullName),
            "listBooking" => $listBookingArr,
            "bookingLength" => count($listBookingArr),
            "content" => "HELLO WORLD"
        ]);
    }

    public function getFirstName($name){
        $explodeName = explode(' ', $name);
        return $explodeName[count($explodeName) - 1];
    }

    public function formatNumberToMoney($number){
        $money = mb_str_split((string)$number);
        $count = 0;
        for( $i = (count($money) - 1); $i > 0 ; $i--){
            $count++;
            if($count == 3){
                array_splice($money, $i, 0, ',');
                $count = 0;
            }
        }
        return implode('', $money);
    }

    public function getProfilePassword(){
        $navBar = GlobalController::getNavbar();
        View::renderTemplate("profile/userProfileChangePass.html", [
            "navbar" => $navBar,
            "passwordPage" => true,
            "user" => get_object_vars(Request::$user),
            "createAt" => date('d/m/Y', strtotime(Request::$user->createAt)),
            "firstName" => self::getFirstName(Request::$user->fullName),
            "content" => "HELLO WORLD"
        ]);
    }

    public function detailBooking(){
        $bookingID = $_POST['bookingID'] ?? 0;
        $isPaid = $_POST['isPaid'] ?? false;
        if($bookingID == 0){
            echo jsone_encode([]);
        }else{
            echo json_encode(self::getInfoRelateToBooking($bookingID, $isPaid=='true'));
        }
    }

    public function getInfoRelateToBooking($bookingID, $isPaid){
        $seatListName = '';
        $foodList = [];
        $seatPrice = 0;
        $foodPrice = 0;
        $total = 0;

        // var_dump($booking->bookingID);
        $foodBookingList = FoodBooking::find(Model::UN_DELETED_OBJ, $bookingID);
        if($foodBookingList != false){
            // var_dump(json_encode($foodBookingList));
            foreach($foodBookingList as $foodBooking){
                $foodPrice += $foodBooking->foodPrice;
                // var_dump(json_encode($foodBooking));
                $food = Food::find(Model::UN_DELETED_OBJ, $foodBooking->foodID);
                if($food != null){
                    $foodName = $food->foodName;
                    $foodUnit = $foodBooking->foodUnit;
                    array_push($foodList, array("foodName" => $foodName, "foodUnit" => $foodUnit));
                }
            }
        }
        $seatBookingList = SeatShowtime::where("bookingID =:bookingID", ["bookingID" => $bookingID]);
        if($seatBookingList != null){
            $showtime = Showtime::find(Model::UN_DELETED_OBJ, $seatBookingList[0]->showID);
            foreach($seatBookingList as $seatBooking){
                $seat = Seat::find(Model::UN_DELETED_OBJ, $seatBooking->seatID);
                if($seat!=null){
                    $seatPrice += $seatBooking->seatPrice;
                    $seatListName .= $seat->seatRow .$seat->seatCol .", ";
                }
            }
            $seatListName = rtrim($seatListName, ', ');
            if($showtime != null){
                $movie = Movie::find(Model::UN_DELETED_OBJ, $showtime->movieID);
                $room = Room::find(Model::UN_DELETED_OBJ, $showtime->roomID);
                if($room != null){
                    $cinema = Cinema::find(Model::UN_DELETED_OBJ, $room->cinemaID);
                    if($cinema != null && $movie != null){
                        $movie = get_object_vars($movie);
                        $cinema = get_object_vars($cinema);
                        $room = get_object_vars($room);
                        $total = $foodPrice + $seatPrice;  
                        $total = self::formatNumberToMoney($total) ."đ";
                        $foodPrice = self::formatNumberToMoney($foodPrice) ."đ";
                        $seatPrice = self::formatNumberToMoney($seatPrice) ."đ";
                        $time = date('H:i d/m/Y', strtotime($showtime->timeStart));
                        $converted_isPaid = $isPaid ? 'true' : 'false';
                        return array("movie" => $movie, "cinema" => $cinema, "room" => $room, "seatListName" => $seatListName, "time" => $time, "total" => $total, "foodList" => $foodList, "bookingID" => $bookingID, "isPaid" => $converted_isPaid, "foodPrice" => $foodPrice, "seatPrice" => $seatPrice);
                    }
                }
            }    
        }
        return [];
    }
}