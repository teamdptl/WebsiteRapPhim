<?php

namespace app\utils;

use app\model\Booking;
use app\model\Food;
use app\model\FoodBooking;
use app\model\Movie;
use app\model\MovieCategory;
use app\model\Room;
use app\model\Seat;
use app\model\SeatShowtime;
use app\model\SeatType;
use app\model\Showtime;
use app\model\ShowtimePrice;
use app\model\User;
use Tmdb\Model\Query\Discover\DiscoverMoviesQuery;
use Tmdb\Repository\DiscoverRepository;
use Tmdb\Repository\MovieRepository;

require_once dirname(__DIR__) . '/../vendor/autoload.php';
require_once dirname(__DIR__) .'/utils/apikey.php';

generateMovies();
generateSeats();
generateShowTimes();
generateBookings();

function generateShowTimes(){
    $topMovie = 5;
    $movies = array_slice(Movie::findAll(), 0, $topMovie);
    $currentDate = time();
    $dayRange = [-10, 10];
    $rooms = Room::findAll();
    $roomSize = count($rooms) - 1;
    foreach ($movies as $movie){
        echo "Đang random cho phim ".$movie->movieName."\n";
        $numberOfShowTimes = rand(10 , 25);
        for ($i=0;$i<$numberOfShowTimes;$i++){
            $showTimes = new Showtime();
            $showTimes->duringTime = $movie->duringTime;
            $showTimes->roomID = $rooms[rand(0, $roomSize)]->roomID;
            $showTimes->movieID = $movie->movieID;
            $randomRange = rand($dayRange[0], $dayRange[1]);
            $randomDay = strtotime("$randomRange day", $currentDate);
            $randomTimestamp = 0;
            if ($randomDay > $currentDate){
                $randomTimestamp = rand($currentDate, $randomDay);
            }
            else {
                $randomTimestamp = rand($randomDay, $currentDate);
            }

            $showTimes->timeStart = date("Y-m-d H:i:s", $randomTimestamp);
            $showId = Showtime::save($showTimes);

            $seatTypes = SeatType::findAll();
            foreach ($seatTypes as $type){
                $seatPrices = new ShowtimePrice();
                $seatPrices->showID = $showId;
                $seatPrices->seatType = $type->seatType;
                if ($type->seatType == 1)
                    $seatPrices->ticketPrice = 80000;
                else $seatPrices->ticketPrice = 85000;
                $seatPrices->discountID = null;
                ShowtimePrice::save($seatPrices);
            }
        }
    }
}

function generateBookings(){
    $showTimes = Showtime::findAll();
    $foods = Food::findAll();
    $users = User::where("permissionID = :permissionID", [
        "permissionID" => 1
    ]);
    if (count($users) == 0)
        return;
    $perMovieBookings = rand(1, 5);
    foreach ($showTimes as $show){
        echo "Đang tạo booking cho showtime $show->showID\n";
        for ($i = 0;$i<$perMovieBookings;$i++){
            $numUser = count($users) - 1;
            $book = new Booking();
            $randomUser = $users[rand(0, $numUser)];
            $book->bookName = $randomUser->fullName;
            $book->bookEmail = $randomUser->email;

            $strTime = strtotime($show->timeStart);
            $minutes_to_subtract = rand(0, 1200);
            $randomBookTime = strtotime("-{$minutes_to_subtract} minutes", $strTime);
            $book->bookTime = date("Y-m-d H:i:s", $randomBookTime);
            $book->methodPay = "Tiền mặt";
            if (rand(0, 1) == 1){
                $book->isPaid = true;
            } else {
                $book->isPaid = false;
            }

            $book->userID = $randomUser->userID;
            $bookID = Booking::save($book);
            $numberBook = rand(1, 6);
            $randomSeatType = rand(1, 2);
            for ($index = 0 ;$index <$numberBook;$index++){
                $seatShow = new SeatShowtime();
                $listSeatEmpty = Seat::query("SELECT seat.*, showtime_price.ticketPrice FROM `seat` INNER JOIN showtime ON seat.roomID = showtime.roomID INNER JOIN showtime_price ON showtime_price.seatType = seat.seatType AND showtime_price.showID = showtime.showID WHERE showtime.showID = :showID AND showtime.roomID = :roomID AND seat.seatID NOT IN ( SELECT seatID FROM seat_showtime WHERE seat_showtime.showID = :showID );", [
                    "showID" => $show->showID,
                    "roomID" => $show->roomID
                ]);
                do {
                    $randomSeat = $listSeatEmpty[rand(0, count($listSeatEmpty) - 1)];
                } while ($randomSeat->seatType != $randomSeatType);
                $seatShow->showID = $show->showID;
                $seatShow->seatID = $randomSeat->seatID;

                $seatShow->pickedAt = date('Y-m-d H:i:s', $randomBookTime);
                $seatShow->seatPrice = $randomSeat->ticketPrice;
                $seatShow->userID = $randomUser->userID;
                $seatShow->bookingID = $bookID;
                SeatShowtime::save($seatShow);
            }

            $food = $foods[rand(0, count($foods) - 1)];
            $foodBook = new FoodBooking();
            $foodBook->bookingID = $bookID;
            $foodBook->foodID = $food->foodID;
            $foodBook->foodPrice = $food->foodPrice;
            $foodBook->foodUnit = rand(1, 4);
            FoodBooking::save($foodBook);
        }
    }
}

function generateSeats(){
    // Tạo tự động danh sách ghế
    $rooms = Room::findAll();
    $alphabet = range('A', 'Z');
    $seatVIP = 2;
    $seatNormal = 1;
    $ratio = 0.6; // 60% là ghế thường
    echo "Vui lòng chờ tạo ghế trong khoảng 1 phút \n";
    foreach ($rooms as $room){
        echo "Đã tạo ghế xong cho phòng $room->roomID !\n";
        // Lấy số dòng số cột
        $rows = $room->numberOfRow;
        $cols = $room->numberOfCol;
        for ($i=0;$i<$rows;$i++){
            if ($i < ceil($rows * $ratio))
                $currentType = $seatNormal;
            else
                $currentType = $seatVIP;
            for ($j=0;$j<$cols;$j++){
                $seat = new Seat();
                $seat->seatRow = $alphabet[$i];
                $seat->seatCol = $j + 1;
                $seat->seatType = $currentType;
                $seat->roomID = $room->roomID;
                Seat::save($seat);
            }
        }
    }
}

function generateMovies(){
    $client = require_once dirname(__DIR__) .'/utils/set-up-client.php';
    $defaultLandScape = "https://img.freepik.com/free-vector/film-strip-with-light-effect-cinema-background_1017-38171.jpg";
    $query = new DiscoverMoviesQuery();
    $repository = new DiscoverRepository($client);
    $repositoryMovie = new MovieRepository($client);

//$dateStart = new DateTime('2020-04-24');
//$dateEnd = new DateTime('2025-04-24');
    $currentPage = 1;
    $maxPage = 1;
    while ($currentPage <= $maxPage ){
        $query
            ->page($currentPage)
            ->language('vi')
            ->includeVideo()
            ->sortBy("popularity.desc")
            ->watchRegion("VN")
            ->filterLanguage(["en", "vi", "es", "ja"]);
//        ->primaryReleaseDateGte($dateStart)
//        ->primaryReleaseDateLte($dateEnd);

        $response = $repository->discoverMovies($query);
        $maxPage = min($response->getTotalPages(), 6);
        echo ("Current page: $currentPage, maxPage: $maxPage\n");
        $currentPage++;
        foreach ($response->getIterator() as $movie) {
            // Kiểm tra xem id này đã lưu trong database chưa nếu có rồi thì bỏ qua
            $isExist = \app\model\Movie::where("externalID = :externalID",[
                "externalID" => $movie->getId()
            ]);

            if ($isExist) continue;

            $movieDetails = $repositoryMovie->load($movie->getId(), [
                'language' => 'vi'
            ]);

            $dbMovie = new \app\model\Movie();
            $dbMovie->movieName = $movieDetails->getTitle();
            echo $movieDetails->getTitle() ."\n";
            $dbMovie->movieDes = $movieDetails->getOverview();
            $postLink = "https://www.themoviedb.org/t/p/w600_and_h900_bestv2".$movieDetails->getPosterPath();
            $dbMovie->posterLink = $postLink;
            $dbMovie->landscapePoster = "https://image.tmdb.org/t/p/original".$movieDetails->getBackdropPath() ?? $defaultLandScape;
            $dbMovie->externalID = $movieDetails->getId();
            // Lấy video trailer
            foreach ($movieDetails->getVideos() as $trailer) {
                $dbMovie->trailerLink = $trailer->getKey();
                break;
            }
            // Gắn default video
            if ($dbMovie->trailerLink == ""){
                $dbMovie->trailerLink = "dQw4w9WgXcQ";
            }

            // Lấy danh sách 2 diễn viên đầu tiên
            $count = 0;
            $personNameList = "";
            foreach ($movieDetails->getCredits()->getCast() as $person) {
                $personNameList .= $person->getName() . ", ";
                $count++;
                if ($count > 1) {
                    break;
                }
            }
            $personNameList = trim($personNameList, ", ");
            $dbMovie->movieActors = $personNameList;

            // Lấy thông tin đạo diễn
            $director = "Không rõ";
            foreach ($movieDetails->getCredits()->getCrew() as $crew) {
                if ($crew->getJob() == "Director") {
                    $director = $crew->getName();
                    break;
                }
            }
            $dbMovie->movieDirectors = $director;

            $dbMovie->duringTime = $movieDetails->getRuntime();
            if ($movieDetails->getReleaseDate() != "")
                $dbMovie->dateRelease = $movieDetails->getReleaseDate()?->format('Y-m-d H:i:s');
            else
                $dbMovie->dateRelease = date("Y-m-d h:i:s");
            // Lấy các ngôn ngữ trong phim
            $spokenLang = "";
            foreach ($movieDetails->getSpokenLanguages() as $lang) {
                $spokenLang .= $lang->getName() . ", ";
            }
            $dbMovie->movieLanguage = trim($spokenLang, ", ");

            // Gắn tag cho phim
//        if ($movieDetails->getAdult()){
//            $dbMovie->tagID = 2;
//        }
//        else {
//            $dbMovie->tagID = 1;
//        }
            $dbMovie->tagID = rand(1, 4);

            $insertID = \app\model\Movie::save($dbMovie);

            foreach ($movieDetails->getGenres() as $cate){
                $movieCate = new MovieCategory();
                $movieCate->movieID = $insertID;
                $movieCate->categoryID = $cate->getId();
                MovieCategory::save($movieCate);
            }
        }
    }
}



