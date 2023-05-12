<?php

namespace app\controller;

class UploadFileHandle
{
    private static string $upload_path = './src/uploads/';
    public function uploadFile()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($FILES["file"])) {
            $fileName  =  $_FILES['file']['name'];
            $tempPath  =  $_FILES['file']['tmp_name'];
            $fileSize  =  $_FILES['file']['size'];
            if (empty($fileName)) {
                $errorMSG = json_encode(array("message" => "Vui lòng chọn ảnh", "status" => 0));
                echo $errorMSG;
                return;
            }
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
            // allow valid image file formats
            if (in_array($fileExt, $valid_extensions)) {
                //check file not exist our upload folder path
                if (!file_exists(static::$upload_path . $fileName)) {
                    if($fileSize < 5000000){
                        move_uploaded_file($tempPath, static::$upload_path . $fileName);
                        $errorMSG = json_encode(array("message" => "Tải ảnh thành công", "status" => 1));
                    }
                }
                $errorMSG = json_encode(array("message" => "Tải ảnh thất bại", "status" => 0));
            }
        }
    }
}
