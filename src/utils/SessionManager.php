<?php

namespace app\utils;

class SessionManager
{
    private string $signUpEmail = "signUpEmail";
    private string $signUpFullName = "signUpFullName";
    private string $signUpPassword = "signUpPassword";
    private string $signUpOTP = "signUpOTP";
    private string $signUpOTPTimeOut = "signUpOTPTimeOut";
    private string $signInUserID = "signInUserID";

    // Cách dùng:
    // $session = new SessionUtils();
    // $session->signUpEmail = "phuc123@gmail.com";
    // Tự động set giá trị "phuc123@gmail.com" vào $_SESSION["signUpEmail"]

    public function __set($key, $value)
    {
        //kiểm tra xem trong class có tồn tại thuộc tính không
        if (property_exists($this, $key)) {
            //tiến hành gán giá trị
            $this->setSession($this->$key, $value);
        } else {
            die("Không tồn tại thuộc tính $key trong class SessionManager");
        }
    }

    // Cách dùng:
    // $session = new SessionUtils();
    // $session->signUpEmail
    // Trả về email trong session nếu không tồn tại sẽ trả về null

    public function __get($key)
    {
        //kiểm tra xem trong class có tồn tại thuộc tính không
        if (property_exists($this, $key)) {
            //tiến hành lấy giá trị
            return $this->getSession($this->$key);
        } else {
            die("Không tồn tại thuộc tính $key trong class SessionManager");
        }
    }

    protected function getSession(string $key){
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return null;
    }

    protected function setSession(string $key, ?string $value){
        $_SESSION[$key] = $value;
    }

}