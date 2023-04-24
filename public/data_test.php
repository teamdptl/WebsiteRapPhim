<?php

use app\model\Movie;
use app\utils\Database;
use core\Model;

require_once dirname(__DIR__) . '/vendor/autoload.php';

//$is_export = 1;
//$sql = "";
//
//$curl = curl_init();
//curl_setopt_array($curl, array(
//    CURLOPT_RETURNTRANSFER => 1,
//    CURLOPT_URL => 'https://api.themoviedb.org/3/genre/movie/list?api_key=fa200d764658e3a6904922e3fc8a0138&language=vi',
//    CURLOPT_SSL_VERIFYPEER => false
//));
//$resp = curl_exec($curl);
//$categorys = json_decode($resp);
//var_dump($categorys);
//curl_close($curl);
//
//$insertCate = "";
//foreach ($categorys as $cate){
//
//}

$movie = new Movie();
$movie->movieName = "Test inserted";
//$movie->movieID = 0;
//$movie->movieName = "Shazam! Cơn Thịnh Nộ Của Các Vị Thần";
//$movie->movieActors = "Shazam! Fury of the Gods";
//$movie->duringTime=1;
//$movie->posterLink="67ztbGkhtltymsqElMnrfWujsY3.jpg";
//$movie->landscapeLink="nDxJJyA5giRhXx96q1sWbOUjMBI.jpg";
//$movie->trailerLink = "https://www.youtube.com/watch?v=JgggA8Jtzyg&list=RDMMqIW313M4HgA&index=13";
//$movie->movieDirectors="Hello duy";
//$date_string = "2023-03-15";
//$unix_timestamp = strtotime($date_string);
//$movie->dateRelease = date('Y-m-d H:i:s', $unix_timestamp);
//$movie->isFeatured = true;
//$movie->movieLanguage = "en";
//$movie->isDeleted = false;
//$movie->movieDes = "ộ phim tiếp tục câu chuyện về cậu thiếu niên Billy Batson, khi đọc thuộc lòng từ ma thuật \"SHAZAM!\" được biến thành Siêu anh hùng thay thế bản ngã trưởng thành của anh ấy, Shazam.";
//$movie->tagID = 1;

$insertID = Movie::save($movie);
$getMovie = Movie::find(Model::ALL_OBJ, $insertID);
var_dump($getMovie);
////Database::runQuery("INSERT INTO MOVIE_CATEGORY VALUES (9,12)");
////echo "Insert $insertID";
//$cat = \app\model\Category::find(\core\Model::ALL_OBJ, 12);
//$movieCate = new \app\model\MovieCategory();
//$movieCate->movieID = $insertID;
//$movieCate->categoryID = $cat->categoryID;
//
//\app\model\MovieCategory::save($movieCate);