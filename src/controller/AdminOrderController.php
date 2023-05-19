<?php

namespace app\controller;

use app\model\Booking;
use core\View;

class AdminOrderController
{
    public function getOrderPage()
    {
        $fromDate = $_GET['fromDate'] ?? "";
        $toDate = $_GET['toDate'] ?? "";
        $search = $_GET['search'] ?? "";
        $arrangeBy = $_GET['arangeBy'] ?? 'bookTime';
        $orderBy = $_GET['orderBy'] ?? 'DESC';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $bookingSize = 30;
        $navBar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $tempStart = $fromDate;
        $tempEnd = $toDate ." 23:59:59";
        $tempArrange = self::arrageItem($arrangeBy);

        
        $thongKeController = new AdminThongKeController();
        if ($fromDate == "" || $toDate == ""){
            $dates = $thongKeController->getMinMaxDate();
            if ($fromDate == ""){
                $tempStart = $dates["min"];
            }
            if ($toDate == ""){
                $tempEnd = $dates["max"];
            }
        }
        $pageStart = ($page-1)*$bookingSize;
        // $bookings = Booking::query("SELECT booking.*, 
        //                 COALESCE(SUM(seat_showtime.seatPrice), 0) AS seatPrices, 
        //                 COALESCE(SUM(food_booking.foodPrice * food_booking.foodUnit), 0) AS foodPrice, 
        //                 COALESCE(COUNT(seat_showtime.seatID), 0) AS seatNum, 
        //                 COALESCE(SUM( food_booking.foodUnit), 0) AS foodUnits, 
        //                 movie.movieName, 
        //                 cinema.cinemaName
        //             FROM booking 
        //             INNER JOIN seat_showtime ON seat_showtime.bookingID = booking.bookingID
        //             INNER JOIN food_booking ON food_booking.bookingID = booking.bookingID
        //             INNER JOIN seat ON seat.seatID = seat_showtime.seatID
        //             INNER JOIN room ON room.roomID = seat.roomID
        //             INNER JOIN cinema ON cinema.cinemaID = room.cinemaID
        //             INNER JOIN showtime ON showtime.showID = seat_showtime.showID
        //             INNER JOIN movie ON movie.movieID = showtime.movieID
        //             WHERE booking.bookTime > :timeStart AND booking.bookTime <= :timeEnd
        //             GROUP BY booking.bookingID, movie.movieName, cinema.cinemaName ORDER BY $tempArrange $orderBy",
        //     [
        //         "timeStart" => $tempStart,
        //         "timeEnd" => $tempEnd
        //     ]
        // );

        $bookings = Booking::query("SELECT booking.*, 
                                        COALESCE(SUM(seat_showtime.seatPrice), 0) AS seatPrices, 
                                        COALESCE(COUNT(seat_showtime.seatID), 0) AS seatNum, 
                                        movie.movieName, 
                                        cinema.cinemaName
                                    FROM booking 
                                    INNER JOIN seat_showtime ON seat_showtime.bookingID = booking.bookingID     
                                    INNER JOIN seat ON seat.seatID = seat_showtime.seatID
                                    INNER JOIN room ON room.roomID = seat.roomID
                                    INNER JOIN cinema ON cinema.cinemaID = room.cinemaID
                                    INNER JOIN showtime ON showtime.showID = seat_showtime.showID
                                    INNER JOIN movie ON movie.movieID = showtime.movieID
                                    WHERE booking.bookTime > :timeStart AND booking.bookTime <= :timeEnd
                                    GROUP BY booking.bookingID ORDER BY $tempArrange $orderBy", 
                                    [
                                        "timeStart" => $tempStart,
                                        "timeEnd" => $tempEnd
                                    ]);
        $bookingFood = Booking::query("SELECT booking.bookingID,    
                                        COALESCE(SUM(food_booking.foodPrice * food_booking.foodUnit), 0) AS foodPrice, 
                                        COALESCE(SUM( food_booking.foodUnit), 0) AS foodUnits
                                    FROM booking 
                                    INNER JOIN food_booking ON food_booking.bookingID = booking.bookingID
                                    WHERE booking.bookTime > :timeStart AND booking.bookTime <= :timeEnd
                                    GROUP BY booking.bookingID", 
                                    [
                                        "timeStart" => $tempStart,
                                        "timeEnd" => $tempEnd
                                    ]);

        foreach ($bookings as $key => $booking){
            $booking->isPaidText = ($booking->isPaid == true) ? 'true' : 'false';
            
            if (!$this->filterItem($booking, $search)){
                unset($bookings[$key]);
            }else{
                foreach($bookingFood as $food){
                    if($booking->bookingID == $food->bookingID){
                        $booking->foodPrice = $food->foodPrice;
                        $booking->foodUnits = $food->foodUnits;
                    }
                }
            }
        }

        $maxPage = ceil(count($bookings)/$bookingSize);
        $activePage=  $pageStart;
        $bookings = array_slice($bookings, $pageStart, $bookingSize);
        View::renderTemplate("/admin/admin_order.html", [
            "navbar" => $navBar,
            "navAdmin" => $navAdmin,
            "orders" => $bookings,
            "maxPage" => $maxPage,
            "activePage" => $activePage,
            "search" => $search,
            "fromDate" => $fromDate,
            "toDate" => $toDate,
            "arrangeBy" => $arrangeBy,
            "orderBy" => $orderBy
        ]);
    }

    public function filterItem($item, $search){
        if (str_contains(strtolower($item->bookName), strtolower($search)))
            return true;

        if (str_contains(strtolower($item->bookEmail), strtolower($search)))
            return true;

        if (str_contains(strtolower($item->movieName), strtolower($search)))
            return true;

        if (str_contains(strtolower($item->cinemaName), strtolower($search)))
            return true;

        if (str_contains(strtolower($item->bookingID), strtolower($search)))
            return true;

        return false;
    }

    public function arrageItem($arrangeBy){
        $by = '';
        switch ($arrangeBy) {
            case 'bookingID':
                $by = 'booking.bookingID';
                break;

            case 'bookName':
                $by = 'booking.bookName';
                break;

            case 'bookEmail':
                $by = 'booking.bookEmail';
                break;

            case 'bookTime':
                $by = 'booking.bookTime';
                break;

            case 'bookMovie':
                $by = 'movie.movieName';
                break;

            case 'bookCinema':
                $by = 'cinema.cinemaName';
                break;
            
            default:
                $by = 'booking.bookTime';
                break;
        }

        return $by;
    }

    public function updateIsPaidBooking(){
        $isPaid = $_POST['isPaid'] ?? 'false';
        $bookingID = $_POST['bookingID'] ?? 0;
        $isPaid = ($isPaid == 'true');
        $status = 1;
        $message = '';
        if($bookingID == 0){
            $status = 0;
            $message = "Không tìm thấy hóa đơn số $bookingID";
        }else{
            $booking = Booking::find(1,$bookingID);
            if($booking == null){
                $status = 0;
                $message = "Không tìm thấy hóa đơn số $bookingID";
            }else{
                $booking->isPaid = $isPaid;
                if(Booking::update($booking, $bookingID)){
                    $status = 1;
                    $message = 'Cập nhật thành công';
                }else{
                    $status = 0;
                    $message = 'Cập nhật thất bại';
                }
            }
        }
        echo json_encode(array("status" => $status, "message" => $message));
    }
}
