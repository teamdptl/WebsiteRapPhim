<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$is_export = 1;
$sql = "";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.themoviedb.org/3/genre/movie/list?api_key=fa200d764658e3a6904922e3fc8a0138&language=vi',
    CURLOPT_SSL_VERIFYPEER => false
));
$resp = curl_exec($curl);
$categorys = json_decode($resp);
var_dump($categorys);
curl_close($curl);

$insertCate = "";
foreach ($categorys as $cate){

}
