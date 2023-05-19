<?php 
namespace app\controller;
use core\Controller;
use core\View;
use app\model\User;
use app\utils\Mail;
use app\utils\SessionManager;
use app\utils\Constant;
use core\Request;

class SignUpController extends Controller{

    public function getSignUpPage(){
        $navbar = GlobalController::getNavbar();

        if (Request::$user != null){
            Request::redirect("/");
            return;
        }

        $movieController = new MoviesController();
        $listCinema = $movieController->getFeaturedMovies();
        if ($listCinema == false){
            $listCinema = [];
        }

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
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRecord = $_POST['password_record'];
        $fullname = $_POST['fullname'];

        $fullnameLength = strlen($fullname);
        $emailLength = strlen($email);
        $passwordLength = strlen($password);
        $passwordRecordLength = strlen($passwordRecord);


        $patternEmail = '/^[a-z]+[A-Za-z-_\.0-9]{2,}@[a-z]+[a-z-_\.0-9]{2,}\.[a-z]{2,}$/';
        $patternPassword = '/[\d*A-Za-z@\.]{8,255}/';
        $patternFullname = '/[ A-Za-z]{4,255}/';

        //Check 
        $checkEmail = preg_match($patternEmail, $email);
        $checkPassWord = preg_match($patternPassword, $password);
        $checkFullname = preg_match($patternFullname, $fullname);
        
        $status = 0;
        $message = "Thành công";

        if($fullnameLength==0){
            $message = "Vui lòng nhập họ và tên";
        }
        else if(!$checkFullname){
            $message = "Ít nhất phải có 4 kí tự";
        }
        else if($emailLength==0){
            $message = "Vui lòng nhập email";
        }
        else if(!$checkEmail){
            $message = "Bạn nhập không đúng định dạng Email";
        }
        else if($passwordLength==0){
            $message = "Vui lòng nhập mật khẩu";
        }
        else if(!$checkPassWord){
            $message = "Ít nhất mật khẩu phải có 8 kí tự";
        }
        else if($passwordRecordLength==0){
            $message = "Vui lòng nhập lại mật khẩu";
        }
        else if($password!==$passwordRecord){
            $message = "Không trùng mật khẩu";
        }
        
        else {
        //   $users = User::where("email = '$email' "); // trả về 1 mảng oject
        $users = User::where("email = :email ", compact('email'));
          if($users == null){
           
            $session = new SessionManager();

            if (!isset($session->signUpOTP) && time() < $session->signUpOTPTimeOut) {
                $randomOTP = $session->signUpOTP;
            } else {
                $randomOTP = $this->generateRandomString(); 
            }
            
            $session->signUpOTP = $randomOTP;
            $session->signUpEmail = $email;
            $session->signUpPassword = $password;
            $session->signUpOTPTimeOut = time() + Constant::$otpTimeOut;
            $session->signUpFullName = $fullname;
            

            $status = 1;
            // $message = "OTP la ".$_SESSION["sign_up_otp"]." va email: ".$_SESSION["sign_up_email"];
            $message = "OTP la ".$session->signUpOTP." va email: ".$session->signUpEmail;
            // $message ="OTP la ".$session->signUpOTP;
            // Mail::send($email, "OTP của bạn", "OTP là $randomOTP", "OTP là $randomOTP");
          }
          else
            $message = "Bi trung email";
        }

        $this->jsonSignUpResponse($status, $message);
    }

    public function jsonSignUpResponse($status = 0, $message = "", $email = "", $password = ""){
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "email" => $email,
            "password" => $password
        ]);
    }

    public function validateOTP(){
        $session = new SessionManager();
        $email = $_POST['email'];
        $otp = $_POST["otp"];
        // if (!isset($_SESSION["sign_up_email"]) || !isset($_SESSION["sign_up_otp"])){
        //     $this->jsonSignUpResponse(0, "Email hoặc OTP không tồn tại!");
        //     return;
        // }
        if ($session->signUpEmail == null || $session->signUpOTP == null){
            $this->jsonSignUpResponse(0, "Email hoặc OTP không tồn tại!");
            return;
        }
        
        if (time() > $session->signUpOTPTimeOut){
            $this->jsonSignUpResponse(4, "OTP hết hạn!");
        }else{
            if($session->signUpOTP !== $otp){
                $this->jsonSignUpResponse(3, "OTP sai!");
                return;
            }
    
        }

        
        // if($email == $_SESSION["sign_up_email"] && time() <  $_SESSION["sign_up_timeout"] && $_SESSION["sign_up_otp"] == $otp ){
        if($email == $session->signUpEmail && time() < $session->signUpOTPTimeOut && $session->signUpOTP  == $otp){
            $user = new User();
            $user->fullName = $session->signUpFullName;
            $user->userPassword = $session->signUpPassword;
            $user->email = $session->signUpEmail;
            $user->isActive = true;
            $user->createAt = date_create_from_format('m/d/Y h:i:s', date('m/d/Y h:i:s', time()))->format('Y-m-d H:i:s');
            $user->permissionID = NULL;
            User::save($user);
            $this->jsonSignUpResponse(1, "OTP đúng! Đã tạo user thành công", $user->email, $user->userPassword);
            
        }
       
    }


    public function hasAuthority(): array
    {
        // TODO: Implement hasAuthority() method.
        return [];
    }
}