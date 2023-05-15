<?php

namespace app\utils;

use app\model\MovieCategory;
use app\model\Room;
use app\model\Seat;
use Tmdb\Model\Query\Discover\DiscoverMoviesQuery;
use Tmdb\Repository\DiscoverRepository;
use Tmdb\Repository\MovieRepository;

require_once dirname(__DIR__) . '/../vendor/autoload.php';
require_once dirname(__DIR__) .'/utils/apikey.php';

generateMovies();
generateSeats();

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
        $currentPage++;
        $maxPage = min($response->getTotalPages(), 6);
        echo ("Current page: $currentPage, maxPage: $maxPage\n");

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



