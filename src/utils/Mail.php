<?php

namespace app\utils;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
    private static string $hostname = "smtp-mail.outlook.com";
    private static string $username = "Curtis.Jacobsonh7a@outlook.com";
    private static string $password = "0iwn7tk971";
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
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
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
}