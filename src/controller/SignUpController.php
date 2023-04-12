<?php 
namespace app\controller;
use core\Controller;
use core\View;
use app\model\User;
use app\utils\Mail;

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

    public function generateRandomString($length = 5) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function validateLogup(){
        $email = $_POST['username'];
        $password = $_POST['password'];
        $passwordRecord = $_POST['password_record'];
        $fullname = $_POST['fullname'];

        $patternEmail = '/^[a-z]+[a-z-_\.0-9]{2,}@[a-z]+[a-z-_\.0-9]{2,}\.[a-z]{2,}$/';
        $patternPassword = '/[\dA-Za-z]{8,255}/';
        $patternFullname = '/[ A-Za-z]{4,255}/';

        //Check 
        $checkEmail = preg_match($patternEmail, $email);
        $checkPassWord = preg_match($patternPassword, $password);
        $checkPassWordRecord = preg_match($patternPassword, $password);
        $checkFullname = preg_match($patternFullname, $fullname);
        
        $status = 0;
        $message = "Thành công";

        if(!$checkFullname){
            // echo "Ít nhất phải có 4 kí tự";
            $message = "Ít nhất phải có 4 kí tự";
        }

        else if(!$checkEmail){
            // echo "Bạn nhập không đúng định dạng Email";
            $message = "Bạn nhập không đúng định dạng Email";
        }
        else if($password!==$passwordRecord){
            // echo "Không trùng mật khẩu";
            $message = "Không trùng mật khẩu";
        }

        else if(!$checkPassWord){
            $message = "Ít nhất phải có 8 kí tự";
        }

        else {
          $users = User::where("email = '$email' "); // trả về 1 mảng oject
          if($users == null){
            $randomOTP = $this->generateRandomString();
            $_SESSION["sign_up_otp"] = $randomOTP;
            $_SESSION["sign_up_email"] = $email;
            $_SESSION["sign_up_password"] = $password;
            $_SESSION["sign_up_fullname"] = $fullname;
            $_SESSION["sign_up_timeout"] = time() + 5*60*1000;
            $status = 1;
            $message = "OTP la ".$_SESSION["sign_up_otp"]." va email: ".$_SESSION["sign_up_email"];
            // Mail::send($email, "OTP của bạn", "OTP là $randomOTP", "OTP là $randomOTP");

          }
          else
            $message = "Bi trung email";
        }

        $this->jsonSignUpResponse($status, $message);
    }

    public function jsonSignUpResponse($status = 0, $message = ""){
        echo json_encode([
            "status" => $status,
            "message" => $message,
        ]);
    }

    public function validateOTP(){
        $email = $_POST['email'];
        $otp = $_POST["otp"];
        if (!isset($_SESSION["sign_up_email"]) || !isset($_SESSION["sign_up_otp"])){
            $this->jsonSignUpResponse(0, "Email hoặc OTP không tồn tại!");
            return;
        }
        if($email == $_SESSION["sign_up_email"] && time() <  $_SESSION["sign_up_timeout"] && $_SESSION["sign_up_otp"] == $otp ){
            $user = new User();
            $user->username = $_SESSION["sign_up_email"];
            $user->password = $_SESSION["sign_up_password"];
            $user->email = $_SESSION["sign_up_email"];
            $user->phone = "";
            $user->is_active = true;
            $user->is_admin = false;
            $user->is_verify = true;
            $user->created_at = date_create_from_format('m/d/Y h:i:s', date('m/d/Y h:i:s', time()))->format('Y-m-d H:i:s');
            $user->permissionID = NULL;
            // public int $userID;
            // public string $username;
            // public string $password;
            // public string $email;
            // public string $phone;
            // public bool $is_active;
            // public bool $is_admin;
            // public bool $is_verify;
            // public string $created_at;
            // public ?int $permissionID;
            User::save($user);
            $this->jsonSignUpResponse(1, "OTP đúng! Đã tạo user thành công");
        }
        else {
            $this->jsonSignUpResponse(1, "OTP sai!");
        }
    }



   

    
}