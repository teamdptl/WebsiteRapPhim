<?php

namespace app\controller;

use app\model\Booking;
use app\model\Cinema;
use app\model\Food;
use app\model\FoodBooking;
use app\model\Movie;
use app\model\Room;
use app\model\SeatShowtime;
use core\Application;
use core\Controller;
use core\View;
use stdClass;

class AdminThongKeController extends Controller
{
    public function getThongKePage(){
        $fromDate = $_GET['fromDate'] ?? false;
        $toDate = $_GET['toDate'] ?? false;
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

        $bookQueryId = "SELECT bookingID from booking where bookTime >= '$tempStart' AND bookTime <= '$tempEnd' AND isPaid = true";
        $bookings = $this->filterBookings($tempStart, $tempEnd);
        $cinemaInfo = $this->getCinemaDataInDatabase($bookQueryId);
        $movieInfo = $this->getListBookingMovieDatabase($bookQueryId, $movieSize);
        $foodInfo = $this->getFoodBookingInDatabase($bookQueryId);
        $bookGraphs = $this->getMovieGraphByBookings($bookQueryId);
        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        View::renderTemplate("/admin/admin_thongke.html",[
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
            "fromDate" => $fromDate,
            "toDate" => $toDate,
            "movieSize" => $movieSize,
            "totalOrders" => count($bookings),
            "totalMovieMoney" => $movieInfo["sum"],
            "totalFoodMoney" => $foodInfo["sum"],
            "movieList" =>  $movieInfo["list"],
            "foodList" => $foodInfo["list"],
            "cinemaList" => $cinemaInfo["list"],
            "labels" => $bookGraphs["labels"],
            "foodLine" => $bookGraphs["foodLine"],
            "ticketLine" => $bookGraphs["ticketLine"]
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

    public function getListBookingMovieDatabase($bookQuery, $movieSize=10){
        $movies = Movie::query("SELECT movie.movieName, COALESCE(sum(seat_showtime.seatPrice), 0) as doanhThu FROM MOVIE 
                    INNER JOIN showtime ON showtime.movieID = movie.movieID
					INNER JOIN seat_showtime ON seat_showtime.showID = showtime.showID
                    WHERE seat_showtime.bookingID IN ($bookQuery)
                    GROUP BY movie.movieID
                    ORDER BY doanhThu DESC LIMIT $movieSize");
        return [
                "list"=>$movies,
                "sum"=>array_reduce($movies, function ($sum, $obj) {
                    return $sum += $obj->doanhThu;
                })
        ];
    }

    public function getFoodBookingInDatabase($bookQuery){
        $foods = Food::query("SELECT food.foodImage, food.foodName, IFNULL(SUM(food_booking.foodUnit*food_booking.foodPrice), 0) AS doanhThu, IFNULL(SUM(food_booking.foodUnit), 0) AS soLuong FROM `food` 
                     LEFT JOIN food_booking ON food_booking.foodID = food.foodID AND food_booking.bookingID IN ($bookQuery) 
                     WHERE food.isDeleted = false
                     GROUP BY food.foodID
                     ORDER BY doanhThu DESC");
        return [
            "list"=>$foods,
            "sum"=>array_reduce($foods, function($sum, $obj){
                return $sum += $obj->doanhThu;
            })
        ];
    }

    public function getCinemaDataInDatabase($bookQuery){
        $cinemas = Cinema::query("SELECT cinema.cinemaID, cinema.cinemaName, (SELECT COUNT(DISTINCT room.roomID) FROM room WHERE room.cinemaID = cinema.cinemaID) AS soPhong, count(seat_showtime.seatID) as soVe, sum(seat_showtime.seatPrice) as doanhThu FROM cinema
	            LEFT JOIN room on cinema.cinemaID = room.cinemaID
                LEFT JOIN seat on seat.roomID = room.roomID
                LEFT JOIN seat_showtime on seat_showtime.seatID = seat.seatID AND seat_showtime.bookingID IN ($bookQuery) AND seat_showtime.isBooked = true
	            GROUP BY cinemaID
                ORDER BY doanhThu DESC");
        return [
            "list"=>$cinemas,
        ];
    }

    public function getMovieGraphByBookings($bookQuery){
        $bookingList = Booking::query("SELECT DATE(booking.bookTime) AS bookDate,
                    SUM(IFNULL((SELECT SUM(food_booking.foodUnit * food_booking.foodPrice) FROM food_booking WHERE food_booking.bookingID = booking.bookingID), 0)) AS foodSum,
                    SUM(IFNULL((SELECT SUM(seat_showtime.seatPrice) FROM seat_showtime WHERE seat_showtime.bookingID = booking.bookingID), 0)) AS ticketSum
                FROM booking
                WHERE booking.bookingID IN ($bookQuery)
                GROUP BY bookDate
                ORDER BY bookDate ASC");

        $data = [
            "labels"=> [],
            "foodLine"=>[],
            "ticketLine"=>[]
        ];

        foreach ($bookingList as $booking){
            $data["labels"][] = date("d-m-Y", strtotime($booking->bookDate));
            $data["foodLine"][] = $booking->foodSum;
            $data["ticketLine"][] = $booking->ticketSum;
        }
        return $data;
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
            $totalMoney += $booking->doanhThuVe + $booking->doanhThuDoAn;
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
                if ($foodBooking != false){
                    $food->doanhThu += $foodBooking->foodPrice * $foodBooking->foodUnit;
                    $food->units += $foodBooking->foodUnit;
                }
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

    public function hasAuthority(): array
    {
        return [Application::$quanly];
    }
}