<?php 
namespace app\controller;
use core\Controller;
use core\View;

class SignUpController extends Controller{

    public function getSignUpPage(){
        $navbar = GlobalController::getNavbar();

        $listCinema =[
            [
                "title" => "Quá đã",
                "year" => 2023,
                "category" => "giật giật, đáng ghét",
                "url" => "/assets/slider1.jpg"

            ],
            [
                "title" => "Quá phê",
                "year" => 2023, 
                "category" => "giật giật, đáng ghét",
                "url" => "/assets/slider2.jpg"
            ],
            [
                "title" => "Quá sướng",
                "year" => 2023,
                "category" => "giật giật, đáng ghét",
                "url" => "/assets/slider3.jpg"
            ],
            
        ];

        View::renderTemplate('signUp/signUp_page.html',[
            "navbar" => $navbar,
            "listCinema" => $listCinema
        ]);
      
    }

    public function validateLogup(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fullname = $_POST['fullname'];

        $patternEmail = '/^[a-z]+[a-z-_\.0-9]{2,}@[a-z]+[a-z-_\.0-9]{2,}\.[a-z]{2,}$/';
        $patternPassword = '/[\dA-Za-z]{8,}/';
        $patternFullname = '/[ A-Za-z]{4,}/';

        //Check 
        $checkEmail = preg_match($patternEmail, $username);
        $checkPassWord = preg_match($patternPassword, $password);
        $checkFullname = preg_match($patternFullname, $fullname);

        if($checkEmail && $checkPassWord && $checkFullname){
            echo "Thành công";
        }else echo "Thất bại";

        // if($checkEmail && $checkPassWord){
        //     echo "Thành công";
        // }else echo "Thất bại";
    }
}