<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
use app\utils\Mail;

// Email rất có thể sẽ vào hộp thư spam
$toUser = "huykhaduy@gmail.com";
$title = "Bạn đã gửi yêu cầu lấy lại mật khẩu";
$body = "<p>Mã otp của bạn là 12345</p>";
$isSend = Mail::send($toUser, $title, $body, $body);
if ($isSend) {
    echo "Send successfully!";
}
else {
    echo "Send failed!";
}