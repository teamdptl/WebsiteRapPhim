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

        $movieController = new MoviesController();


        $listCinema = $movieController->getFeaturedMovies();
        if ($listCinema == false){
            $listCinema = [];
        }

        View::renderTemplate('signIn/signIn_page.html',[
            "navbar" => $navbar,
            "listCinema" => $listCinema
        ]);
      
    }

    public function validateLogin(){

        $status = 0;
        $message = "Thành công";
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        $emailLength = strlen($email);
        $passwordLength = strlen($password);

        $patternEmail = '/^[a-z]+[A-Za-z-_\.0-9]{2,}@[a-z]+[a-z-_\.0-9]{2,}\.[a-z]{2,}$/';

        $checkEmail = preg_match($patternEmail, $email);

        if($emailLength==0){
            $message = "Vui lòng nhập email";
        }
        else if(!$checkEmail){
            $message = "Vui lòng nhập đúng định dạng email";
        }
        else if($passwordLength == 0) {
            $message = "Vui lòng nhập mật khẩu";
        }
        else {
            $user = User::where("email = :email AND isDeleted = 0 AND isActive = true", compact('email'));
            if ($user) {
                if ($user[0]->userPassword == $password) {
                    $message = "Thành công";
                    $session = new SessionManager();
                    if (!isset($_SESSION["userID"])) {
                        $session->signInUserID = $user[0]->userID;
                    }
                    $status = 1;
                } else {
                    $message = "Sai mật khẩu";
                }
            }else {
                $userDeleted = User::where("email = :email AND (isDeleted = 1 OR isActive = false)", compact('email'));
                if ($userDeleted) {
                    $message = "Tài khoản đã bị khóa hoặc bị xóa";
                } else {
                    $message = "Email chưa được tạo";
                }
            }
    
        }

        $this->jsonSignInResponse($status, $message);
      
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

        $emailLength = strlen($email);
        $passwordLength = strlen($password);
        $passwordRecordLength = strlen($passwordRecord);

        $patternEmail = '/^[a-z]+[a-z-_\.0-9]{2,}@[a-z]+[a-z-_\.0-9]{2,}\.[a-z]{2,}$/';
        $patternPassword = '/[\dA-Za-z]{8,255}/';

        $checkEmail = preg_match($patternEmail, $email);
        $checkPassWord = preg_match($patternPassword, $password);

        $status = 0;
        $message = "Thành công";

        if($emailLength == 0) {
            $message = "Vui lòng nhập email";
        }
        else if(!$checkEmail){
            $message = "Bạn nhập không đúng định dạng Email";
        }
        else if($passwordLength == 0){
            $message = "Vui lòng nhập password";
        }
        else if(!$checkPassWord){
            $message = "Ít nhất phải có 8 kí tự";
        }
        else if($passwordRecordLength == 0){
            $message = "Vui lòng xác nhập xác nhận lại password";
        }
        else if($password!==$passwordRecord){
            $message = "Không trùng mật khẩu";
        }
        else{
            $users = User::where("email = :email AND isDeleted = 0 ", compact('email'));
            if($users != null){
                $session = new SessionManager();

                if (!isset($session->signUpOTP) && time() < $session->signUpOTPTimeOut) {
                    $randomOTP = $session->signInOTP;
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
            else $message = "Email chưa được tạo hoặc đã bị xóa khỏi hệ thống";

        }

        $this->jsonSignInResponse($status, $message);

    }

    public function jsonSignInResponse($status = 0, $message = ""){
        echo json_encode([
            "status" => $status,
            "message" => $message
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
        if ($session->signInEmailChangePassword == null || $session->signInOTP == null){
            $this->jsonSignInResponse(0, "Email hoặc OTP không tồn tại!");
            return;
        }
        
        if (time() > $session->signInOTPTimeOut){
            $this->jsonSignInResponse(4, "OTP hết hạn!");
        }else{
            if($session->signInOTP !== $otp){
                $this->jsonSignInResponse(3, "OTP sai!");
                return;
            }
    
        }

        
        // if($email == $_SESSION["sign_up_email"] && time() <  $_SESSION["sign_up_timeout"] && $_SESSION["sign_up_otp"] == $otp ){
        if($email == $session->signInEmailChangePassword && time() < $session->signInOTPTimeOut && $session->signInOTP  == $otp){
            // $user = new User();
            // $user->fullName = $session->signUpFullName;
            // $user->userPassword = $session->signUpPassword;
            // $user->email = $session->signUpEmail;
            // $user->isActive = true;
            // $user->createAt = date_create_from_format('m/d/Y h:i:s', date('m/d/Y h:i:s', time()))->format('Y-m-d H:i:s');
            // $user->permissionID = NULL;
            // User::save($user);

            $email = $session->signInEmailChangePassword;
            $users = User::where("email = :email AND isDeleted = 0 ", compact('email'));



            $users[0]->userPassword = $session->signInChangePassword;

            User::update($users[0],  $users[0]->userID);
            

            $this->jsonSignInResponse(1, "OTP đúng! Đã thay đổi mật khẩu thành công thành công");
            
        }
    }
    public function hasAuthority(): array
    {
        return [];
    }
}