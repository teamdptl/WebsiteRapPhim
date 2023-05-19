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

// --------------------- NON LOGIN PAGE --------------------------
// Trang chủ
$app->router->get('/', ["controller"=>"HomeController", "action"=>"homePageRender"]);

// Đăng nhập
$app->router->get('/signin', ['controller' => 'SignInController', 'action' => 'getSignPage']);
$app->router->post('/signin', ["controller" => "SignInController", "action" => "validateLogin"]);
$app->router->post('/signin/otp', ["controller" => "SignInController", "action" => "validateOTP"]);
$app->router->post('/signin/changePassword', ["controller" => "SignInController", "action" => "validateChangePassword"]);

// Đăng kí
$app->router->get('/signup', ['controller' => 'SignUpController', 'action' => 'getSignUpPage']);
$app->router->post('/signup', ["controller" => "SignUpController", "action" => "validateLogup"]);
$app->router->post('/signup/otp', ["controller" => "SignUpController", "action" => "validateOTP"]);

// Trang phim
$app->router->get('/movies', ["controller" => "MoviesController", "action" => "getMoviesPageTest"]);
$app->router->get('/moviesSearch', ["controller" => "MoviesController", "action" => "searchMovie"]);
$app->router->post('/moviesTest', ["controller" => "MoviesController", "action" => "searchMovie"]);

// Chi tiết phim
$app->router->post('/movies/{id:\d+}', ["controller" => "DetailMovieController", "action" => "renderShowTime"]);
$app->router->get('/movies/{id:\d+}', ["controller" => "DetailMovieController", "action" => "getDetailMoviePage"]);

// Trang rạp
$app->router->get('/cinemas', ["controller" => "CinemaController", "action" => "getCinemaPage"]);

// --------------------- LOGIN REQUIRE PAGE --------------------------
// Đăng xuất: admin, user, quan ly, nhan vien
$app->router->post('/logout', ["controller" => "LogoutController", "action" => "logoutHandle"]);

// Trang thông tin người dùng: user, quan ly
$app->router->get('/profile', ["controller" => "UserProfileController", "action" => "getProfilePage"]);
$app->router->get('/profile/password', ["controller" => "UserProfileController", "action" => "getProfilePassword"]);
$app->router->post('/profile', ['controller' => "UserProfileController", "action" => "detailBooking"]);

// Đặt phim: user
$app->router->get('/booking', ["controller"=>"BookingController", "action"=>"getBookingPage"]);
$app->router->post('/booking', ["controller"=>"BookingController", "action"=>"isAbleSeats"]);
$app->router->post('/booking/payment', ["controller"=>"BookingController", "action"=>"handleInfoForPaymentPage"]);
$app->router->post('/payment', ["controller"=>"BookingController", "action"=>"finalPayment"]);

// Quản lý phim: quan ly, nhan vien
$app->router->get('/adminQuanLyPhim', ["controller" => "AdminQuanLyPhimController", "action" => "getAdminQuanLyPhim"]);
$app->router->get('/adminQuanLyPhim/getMovieID', ["controller" => "AdminQuanLyPhimController", "action" => "getOneMovie"]);
$app->router->post('/adminQuanLyPhim/ThemPhim', ["controller" => "AdminQuanLyPhimController", "action" => "AddMovie"]);
$app->router->post('/adminQuanLyPhim/XoaPhim', ["controller" => "AdminQuanLyPhimController", "action" => "DeleteMovie"]);
$app->router->post('/adminQuanLyPhim/SuaPhim', ["controller" => "AdminQuanLyPhimController", "action" => "UpdateMovie"]);

// Quản lý lịch chiếu: quan ly, nhan vien
$app->router->get('/adminShowTime/getMovieName', ["controller" => "AdminShowTimeController", "action" => "getMovieName"]);
$app->router->get('/adminShowTime/getRoomName', ["controller" => "AdminShowTimeController", "action" => "getRoomName"]);
$app->router->post('/adminShowTime/insert', ["controller" => "AdminShowTimeController", "action" => "insertShowTime"]);
$app->router->get('/adminShowTime/edit', ["controller" => "AdminShowTimeController", "action" => "editShowTime"]);
$app->router->post('/adminShowTime/edit', ["controller" => "AdminShowTimeController", "action" => "updateShowTime"]);
$app->router->post('/adminShowTime/del', ["controller" => "AdminShowTimeController", "action" => "delShowTime"]);
$app->router->get('/adminShowTime', ["controller" => "AdminShowTimeController", "action" => "getAdminShowTimePage"]);

// Quản lý thức ăn: quan ly, nhan vien
$app->router->get('/adminFood', ["controller" => "AdminFoodController", "action" => "getAdminFoodPage"]);
$app->router->post('/adminFood/insert', ["controller" => "AdminFoodController", "action" => "insertFood"]);
$app->router->get('/adminFood/edit', ["controller" => "AdminFoodController", "action" => "editFood"]);
$app->router->post('/adminFood/update', ["controller" => "AdminFoodController", "action" => "updateFood"]);
$app->router->post('/adminFood/del', ["controller" => "AdminFoodController", "action" => "delFood"]);

// Quản lý người dùng: quan ly, admin
$app->router->get('/adminQuanLyTaiKhoan', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "getPage"]);
$app->router->get('/adminQuanLyTaiKhoan/user', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "findUserBySearch"]);
$app->router->get('/adminQuanLyTaiKhoan/getUserId', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "getUserId"]);
$app->router->get('/adminQuanLyTaiKhoan/getAllGroup', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "getAllGroup"]);
$app->router->post('/adminQuanLyTaiKhoan/deleteUser', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "deleteUser"]);
$app->router->post('/adminQuanLyTaiKhoan/saveUser', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "saveUser"]);
$app->router->post('/adminQuanLyTaiKhoan/editUser', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "editUser"]);
$app->router->post('/adminQuanLyTaiKhoan/recovery', ["controller" => "AdminQuanLyTaiKhoanController", "action" => "accountRecovery"]);

// Quản lý thống kê: quan ly
$app->router->get("/adminThongKe", ["controller" => "AdminThongKeController", "action" => "getThongKePage"]);

// Quản lý đơn hàng: quan ly
$app->router->get("/adminDonHang", ["controller" => "AdminOrderController", "action" => "getOrderPage"]);
$app->router->post("/adminDonHang", ["controller" => "AdminOrderController", "action" => "updateIsPaidBooking"]);


//$app->router->get('/{error:\S+}', function($error){
//    $navBar = \app\controller\GlobalController::getNavbar();
//    View::renderTemplate("/template/404.html", [
//        "navbar" => $navBar,
//        "errorText" => "Bạn đang truy cập trang $error không tồn tại"
//    ]);
//});

//$app->router->post('/upload', ["controller" => "UploadFileHandle", "action" => "uploadFile"]);


// Running and resolver
$app->run();

