<?php

namespace app\controller;

use app\model\Booking;
use core\View;

class AdminOrderController
{
    public function getOrderPage()
    {
        $fromDate = $_GET['fromDate'] ?? false;
        $toDate = $_GET['toDate'] ?? false;
        $search = $_GET['search'] ?? "";
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $bookingSize = 30;
        $navBar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $tempStart = $fromDate;
        $tempEnd = $toDate;

        $thongKeController = new AdminThongKeController();
        if ($fromDate == false || $toDate == false){
            $dates = $thongKeController->getMinMaxDate();
            if ($fromDate == false){
                $tempStart = $dates["min"];
            }
            if ($toDate == false){
                $tempEnd = $dates["max"];
            }
        }
        $pageStart = ($page-1)*$bookingSize;
        $bookings = Booking::query("SELECT booking.*, 
                        COALESCE(SUM(seat_showtime.seatPrice), 0) AS seatPrices, 
                        COALESCE(SUM(food_booking.foodPrice * food_booking.foodUnit), 0) AS foodPrice, 
                        COALESCE(COUNT(seat_showtime.seatID), 0) AS seatNum, 
                        COALESCE(SUM( food_booking.foodUnit), 0) AS foodUnits, 
                        movie.movieName, 
                        cinema.cinemaName
                    FROM booking 
                    INNER JOIN seat_showtime ON seat_showtime.bookingID = booking.bookingID
                    LEFT JOIN food_booking ON food_booking.bookingID = booking.bookingID
                    INNER JOIN seat ON seat.seatID = seat_showtime.seatID
                    INNER JOIN room ON room.roomID = seat.roomID
                    INNER JOIN cinema ON cinema.cinemaID = room.cinemaID
                    INNER JOIN showtime ON showtime.showID = seat_showtime.showID
                    INNER JOIN movie ON movie.movieID = showtime.movieID
                    WHERE booking.bookTime > :timeStart AND booking.bookTime <= :timeEnd
                    GROUP BY booking.bookingID, movie.movieName, cinema.cinemaName ORDER BY booking.bookTime DESC",
            [
                "timeStart" => $tempStart,
                "timeEnd" => $tempEnd
            ]
        );

        foreach ($bookings as $key=>$booking){
            if (!$this->filterItem($booking, $search)){
                unset($bookings[$key]);
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
            "activePage" => $activePage
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
}
