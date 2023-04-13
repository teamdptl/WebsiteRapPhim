<?php
namespace app\controller;
use core\Controller;
use core\View;
use core\Request;
use app\model\User;
use app\utils\SessionManager;


class SignInController extends Controller{

    

    public function getSignPage(){
        // Kiem tra da login chua 
        if (Request::$user != null){
            Request::redirect("/");
            return;
        }

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
        $user = User::where("email = :username AND userPassword = :password", compact('username', 'password'));

        if ($user == null){
            echo "Sai mat khau hoac ten dang nhap";
            return;
        }

        $session = new SessionManager();
        if(!isset($_SESSION["userID"])){
            // echo $_SESSION["userID"] = $user[0]["userID"];
            $session->signInUserID = $user[0]->userID;
        }
        
      
    }
}