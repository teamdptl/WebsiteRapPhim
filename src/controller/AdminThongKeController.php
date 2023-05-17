<?php

namespace app\controller;

use app\model\Booking;
use app\model\Cinema;
use app\model\Food;
use app\model\FoodBooking;
use app\model\Movie;
use app\model\Room;
use app\model\SeatShowtime;
use core\View;

class AdminThongKeController
{
    public function getThongKePage(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $movieSize = isset($_GET['movie_size']) ? (int) $_GET['movie_size'] : 10;
        $tempStart = $fromDate;
        $tempEnd = $toDate;
        if ($fromDate == false || $toDate == false){
            $dates = $this->getMinMaxDate();
            if ($fromDate == false){
                $tempStart = $dates["min"];
            }
            if ($toDate == false){
                $tempEnd = $dates["max"];
            }
        }

        $bookings = $this->filterBookings($tempStart, $tempEnd);
        $bookingInfo = $this->getTotalInfo($bookings);
        $movieInfo = $this->getListBookingMovie($bookings, $movieSize);
        $cinemaInfo = $this->getListCinema($bookings);
        $foodInfo = $this->getListFoodBooking($bookings);
        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        View::renderTemplate("/admin/admin_thongke.html",[
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
            "fromDate" => $fromDate,
            "toDate" => $toDate,
            "totalOrders" => count($bookings),
            "bookingInfo" => $bookingInfo,
            "cinemaInfo" => $cinemaInfo,
            "movieInfo" => $movieInfo,
            "movieSize" => $movieSize,
            "foodInfo" => $foodInfo,
        ]);
    }

    public function getMinMaxDate(): array
    {
        $date = [
            "min" => 2147483647,
            "max" => 0,
        ];
        $bookings = Booking::findAll();
        if (count($bookings) == 0) return $date;
        foreach ($bookings as $booking){
            $bookingTimeStamp = strtotime($booking->bookTime);
            if ($bookingTimeStamp < $date["min"]){
                $date["min"] = $bookingTimeStamp;
            }

            if ($bookingTimeStamp > $date["max"]){
                $date["max"] = $bookingTimeStamp;
            }
        }
        $date["min"] = date("Y-m-d", strtotime('-1 day', $date["min"]));
        $date["max"] = date("Y-m-d", strtotime('+1 day', $date["max"]));
        return $date;
    }

    public function filterBookings($timeStart, $timeEnd, $sortType="ASC"): array {
        $bookings = Booking::where("bookTime >= :timeStart AND bookTime <= :timeEnd ORDER BY bookTime $sortType", [
            "timeStart" => $timeStart,
            "timeEnd" => $timeEnd
        ]);
        return $bookings;
    }

    public function getTotalInfo($bookings){
        $totalMoney = 0;
        $listVe = [];
        $listDoAn = [];
        $listDate = [];
        foreach ($bookings as $booking){
            $booking->doanhThuVe = 0;
            $booking->doanhThuDoAn = 0;
            $listSeatBooked = $booking->hasList(SeatShowtime::class);
            $listFoodBooked = $booking->hasList(FoodBooking::class);
            foreach ($listSeatBooked as $seat){
                $booking->doanhThuVe += $seat->seatPrice;
            }

            foreach ($listFoodBooked as $food){
                $booking->doanhThuDoAn += $food->foodPrice * $food->foodUnit;
            }
            $listDate[] = date('d-m-Y', strtotime($booking->bookTime));
            $listVe[] = $booking->doanhThuVe;
            $listDoAn[] = $booking->doanhThuDoAn;
            $totalMoney += $booking->doanhThuVe + $bookings->doanhThuDoAn;
        }
        return [
            "totalMoney" => $totalMoney,
            "listVe" => $listVe,
            "listDoAn" => $listDoAn,
            "listDate" => $listDate,
        ];
    }

    public function getListCinema($bookings){
        $cinemas = Cinema::findAll();
        $bestCinema = null;
        $maxDoanhThu = 0;
        foreach($cinemas as $cine){
            $cine->doanhThuRap = 0;
            $cine->numRooms = count($cine->hasList(Room::class));
            foreach($bookings as $book){
                $seatShowTimes = SeatShowtime::query("SELECT seat_showtime.* FROM seat_showtime INNER JOIN seat ON seat_showtime.seatID = seat.seatID INNER JOIN room ON room.roomID = seat.roomID WHERE room.cinemaID = :cinemaID AND seat_showtime.bookingID = :bookingID",
                    [
                        "cinemaID" => $cine->cinemaID,
                        "bookingID" => $book->bookingID,
                    ]
                );
                $cine->ticketNumbers = count($seatShowTimes) ?? 0;
                foreach($seatShowTimes as $ticket){
                    $cine->doanhThuRap += $ticket->seatPrice;
                }
            }
            if ($cine->doanhThuRap > $maxDoanhThu){
                $maxDoanhThu = $cine->doanhThuRap;
                $bestCinema = $cine;
            }
        }
        return [
            "bestCinema" => $bestCinema,
            "listCinema" => $cinemas,
        ];
    }

    public function getListBookingMovie($bookings, $movieSize = 10){
        $movies = Movie::findAll();
        $maxDoanhThu = 0;
        $bestMovies = null;
        foreach ($movies as $movie){
            $movie->doanhThu = 0;
            foreach ($bookings as $book){
                $seatShowTimes = SeatShowtime::query("SELECT seat_showtime.* FROM seat_showtime INNER JOIN showtime ON showtime.showID = seat_showtime.showID INNER JOIN movie ON movie.movieID = showtime.movieID WHERE movie.movieID = :movieID AND seat_showtime.bookingID = :bookingID",
                    [
                        "movieID" => $movie->movieID,
                        "bookingID" => $book->bookingID,
                    ]
                );
                foreach ($seatShowTimes as $seat){
                    $movie->doanhThu += $seat->seatPrice;
                }
            }
            if ($movie->doanhThu > $maxDoanhThu){
                $bestMovies = $movie;
                $maxDoanhThu = $movie->doanhThu;
            }
        }

        usort($movies, function($first,$second){
            return $first->doanhThu < $second->doanhThu;
        });
        return [
            "bestMovie" => $bestMovies,
            "listDoanhThu" => array_slice($movies, 0, $movieSize)
        ];
    }

    public function getListFoodBooking($bookings){
        $foods = Food::findAll();
        $totalMoney = 0;
        foreach ($foods as $food){
            $food->doanhThu = 0;
            $food->units = 0;
            foreach ($bookings as $booking){
                $foodBooking = FoodBooking::find(1, $booking->bookingID, $food->foodID);
                $food->doanhThu += $foodBooking->foodPrice * $foodBooking->foodUnit;
                $food->units += $foodBooking->foodUnit;
            }
            $totalMoney += $food->doanhThu;
        }
        usort($foods, function ($a, $b) {
            return $a->doanhThu < $b->doanhThu;
        });
        return [
            "totalMoney"=> $totalMoney,
            "listFood" => $foods,
        ];
    }

//    public function getBookingGraphs($bookings){
//        usort($bookings, function ($a, $b) {
//            return strtotime($a->bookTime) > strtotime($b->bookTime);
//        });
//        foreach($bookings as $booking){
//            $listSeatBook = $booking->haveList(SeatShowtime::class);
//            $booking->doanhThuVe = 0;
//            $booking->doanh
//            foreach($listSeatBook as $seatBook){
//
//            }
//        }
//    }
}