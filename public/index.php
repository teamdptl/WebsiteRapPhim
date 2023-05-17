<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use core\Application;
use app\model\Movie;
use app\model\User;
use core\View;
use app\model\Booking;
use core\Model;
use app\model\FeaturePermission;

// Initialize application
$app = Application::init();
session_start();

$app->router->get('/hi', "example.html");
$app->router->get('/test', ["controller"=>"BookingController", "action"=>"getBookingPage"]);
$app->router->get('/', ["controller"=>"HomeController", "action"=>"homePageRender"]);
$app->router->get('/name/{name:\w+}', ["controller"=>"HomeController", "action"=>"otherAction"]);
$app->router->get('/page/{id:\d+}', function($id){ 
    echo "Bạn đang truy cập trang $id";
});

$app->router->get('/phuc', function(){ 
    View::render('dangnhap.php', ["username"=> "Phuc"]);
});

$app->router->get('/signin', ['controller' => 'SignInController', 'action' => 'getSignPage']);
$app->router->get('/signup', ['controller' => 'SignUpController', 'action' => 'getSignUpPage']);
$app->router->get('/booking', ["controller"=>"BookingController", "action"=>"getBookingPage"]);

//$app->router->get('/movies', ["controller" => "MoviesController", "action" => "getMoviesPage"]);
$app->router->get('/movies', ["controller" => "MoviesController", "action" => "getMoviesPageTest"]);
$app->router->get('/moviesSearch', ["controller" => "MoviesController", "action" => "searchMovie"]);
$app->router->get('/cinemas', ["controller" => "CinemaController", "action" => "getCinemaPage"]);

$app->router->get('/movies/{id:\d+}', ["controller" => "DetailMovieController", "action" => "getDetailMoviePage"]);
$app->router->post('/movies/{id:\d+}', ["controller" => "DetailMovieController", "action" => "renderShowTime"]);


$app->router->get('/adminQuanLyPhim', ["controller" => "AdminQuanLyController", "action" => "getAdminQuanLyPhim"]);
$app->router->get('/adminShowTime/getMovieName', ["controller" => "AdminShowTimeController", "action" => "getMovieName"]);
$app->router->get('/adminShowTime/getRoomName', ["controller" => "AdminShowTimeController", "action" => "getRoomName"]);
$app->router->post('/adminShowTime/insert', ["controller" => "AdminShowTimeController", "action" => "insertShowTime"]);
$app->router->get('/adminShowTime/edit', ["controller" => "AdminShowTimeController", "action" => "editShowTime"]);
$app->router->post('/adminShowTime/edit', ["controller" => "AdminShowTimeController", "action" => "updateShowTime"]);
$app->router->post('/adminShowTime/del', ["controller" => "AdminShowTimeController", "action" => "delShowTime"]);
$app->router->get('/adminShowTime', ["controller" => "AdminShowTimeController", "action" => "getAdminShowTimePage"]);

$app->router->get('/adminFood', ["controller" => "AdminFoodController", "action" => "getAdminFoodPage"]);
$app->router->post('/adminFood/insert', ["controller" => "AdminFoodController", "action" => "insertFood"]);
$app->router->get('/adminFood/edit', ["controller" => "AdminFoodController", "action" => "editFood"]);
$app->router->post('/adminFood/update', ["controller" => "AdminFoodController", "action" => "updateFood"]);
    

$app->router->get('/profile', ["controller" => "UserProfileController", "action" => "getProfilePage"]);
$app->router->get('/profile/password', ["controller" => "UserProfileController", "action" => "getProfilePassword"]);


$app->router->get('/{error:\S+}', function($error){
    $navBar = \app\controller\GlobalController::getNavbar();
    View::renderTemplate("/template/404.html", [
        "navbar" => $navBar,
        "errorText" => "Bạn đang truy cập trang $error không tồn tại"
    ]);
});

$app->router->post('/signin', ["controller" => "SignInController", "action" => "validateLogin"]);
$app->router->post('/signup', ["controller" => "SignUpController", "action" => "validateLogup"]);
$app->router->post('/signup/otp', ["controller" => "SignUpController", "action" => "validateOTP"]);
$app->router->post('/signin/otp', ["controller" => "SignInController", "action" => "validateOTP"]);
$app->router->post('/signin/changePassword', ["controller" => "SignInController", "action" => "validateChangePassword"]);
$app->router->post('/moviesTest', ["controller" => "MoviesController", "action" => "searchMovie"]);

$app->router->post('/adminQuanLyPhim/ThemPhim', ["controller" => "AdminQuanLyPhimController", "action" => "AddMovie"]);
$app->router->post('/booking', ["controller"=>"BookingController", "action"=>"isAbleSeats"]);
$app->router->post('/adminQuanLyPhim/XoaPhim', ["controller" => "AdminQuanLyPhimController", "action" => "DeleteMovie"]);
$app->router->post('/adminQuanLyPhim/SuaPhim', ["controller" => "AdminQuanLyPhimController", "action" => "UpdateMovie"]);



$app->router->post('/logout', ["controller" => "LogoutController", "action" => "logoutHandle"]);
$app->router->post('/upload', ["controller" => "UploadFileHandle", "action" => "uploadFile"]);
// Running and resolver
$app->run();

// $movie = Movie::find("movie100");
// var_dump($movie);

// Movie::find(Model::UN_DELETED_OBJ, 'mID1');
// Movie::find(Model::ALL_OBJ, 'mID1');
// Movie::find(Model::ONLY_DELETED_OBJ, 'mID1');
// Movie::find(8,'mID1');

// Booking::find(9, 'bk01');

// FeaturePermission::delete('permissionID1', 'featureID1', 'actionID');
// FeaturePermission::softDelete('permissionID1', 'featureID1', 'actionID');

// Movie::findAll(Model::UN_DELETED_OBJ);
// Movie::findAll(Model::ALL_OBJ);
// Movie::findAll(Model::ONLY_DELETED_OBJ);

