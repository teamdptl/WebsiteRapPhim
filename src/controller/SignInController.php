<?php
namespace app\controller;
use core\Controller;
use core\View;
use core\Request;
use app\model\User;
use app\utils\SessionManager;
use app\utils\Constant;
use app\utils\Mail;


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
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = User::where("email = :email AND userPassword = :password", compact('email', 'password'));

        if ($user == null){
            echo "Sai mat khau hoac ten dang nhap";
            return;
        } 

     
        

        if(!isset($_SESSION["userID"])){
            $_SESSION["userID"] = $user[0]["userID"];
        }
        
      
    }

    public function generateRandomString($length = 5) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function validateChangePassword(){
        $email = $_POST['emailChangePassword'];
        $password = $_POST['passwordChangePassword'];
        $passwordRecord = $_POST['passwordChangePasswordRecord'];

        $patternEmail = '/^[a-z]+[a-z-_\.0-9]{2,}@[a-z]+[a-z-_\.0-9]{2,}\.[a-z]{2,}$/';
        $patternPassword = '/[\dA-Za-z]{8,255}/';

        $checkEmail = preg_match($patternEmail, $email);
        $checkPassWord = preg_match($patternPassword, $password);

        $status = 0;
        $message = "Thành công";

        if(!$checkEmail){
            $message = "Bạn nhập không đúng định dạng Email";
        }
        else if(!$checkPassWord){
            $message = "Ít nhất phải có 8 kí tự";
        }
        else if($password!==$passwordRecord){
            $message = "Không trùng mật khẩu";
        }
        else{
            $users = User::where("email = :email ", compact('email'));
            if($users !== null){
                $session = new SessionManager();

                if (!isset($session->signUpOTP) && time() < $session->signUpOTPTimeOut) {
                    $randomOTP = $session->signUpOTP;
                } else {
                    $randomOTP = $this->generateRandomString(); 
                }

                $session->signInOTP = $randomOTP;
                $session->signInEmailChangePassword = $email;
                $session->signInChangePassword = $password;
                $session->signInOTPTimeOut = time() + Constant::$otpTimeOut;

                $status = 1;
                $message = "OTP la ".$session->signInOTP." va email: ".$session->signInEmailChangePassword;


            }
            else $message = "Email chưa được tạo";

        }

        $this->jsonSignUpResponse($status, $message);

    }

    public function jsonSignUpResponse($status = 0, $message = ""){
        echo json_encode([
            "status" => $status,
            "message" => $message
        ]);
    }
}