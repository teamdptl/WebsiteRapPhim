<?php
namespace app\controller;
use core\Controller;
use core\View;
use app\model\User;


class SignInController extends Controller{

    

    public function getSignPage(){
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

        View::renderTemplate('signIn/signIn_page.html',[
            "navbar" => $navbar,
            "listCinema" => $listCinema
        ]);
      
    }

    public function validateLogin(){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = User::where("email = '$username' AND passwords =  '$password'");
        if ($user == null) echo "Sai mat khau hoac ten dang nhap";
        else echo "Thanh cong";


        
       
    }
}