<?php

namespace app\utils;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
    private static string $hostname = "smtp-mail.outlook.com";
    private static string $username = "Beatrice.Gillzzo@outlook.com";
    private static string $password = "73r7vpv3gt";
    private static int $port = 587;
    private static string $companyName = "Rạp phim CGV";

    public static function send(string $email, string $title, string $body, string $nonHTMLBody): bool{
        $mail = new PHPMailer(true);
        try{
            // Mail config data
            $mail->isSMTP();
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = self::$hostname;
            $mail->SMTPAuth   = true;
            $mail->Username   = self::$username;
            $mail->Password   = self::$password;
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = self::$port;

            // Send data
            $mail->setFrom(self::$username, self::$companyName);
            $mail->addAddress($email);
            $mail->isHTML(true);

            // Content
            $mail->Subject = $title;
            $mail->CharSet = "UTF-8";
            $mail->Body = $body;
            $mail->AltBody = $nonHTMLBody;
            $mail->send();
            return true;
        } catch(Exception $e){
            return false;
        }
    }

//     public static function asyncSendMail(string $email, string $title, string $body, string $nonHTMLBody){
//         register_shutdown_function(array('app\utils\Mail','send'), $email, $title, $body, $nonHTMLBody);
//     }
}