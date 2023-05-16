<?php

namespace app\controller;

use app\model\GroupPermission;
use app\model\User;
use core\Model;
use core\View;
use mysql_xdevapi\Exception;

class AdminQuanLyTaiKhoanController
{
    public function getPage(){
        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $listRemoved = User::findAll(Model::ONLY_DELETED_OBJ);
        $listAvailable= User::findAll(Model::UN_DELETED_OBJ);
        View::renderTemplate("/admin/admin_user_manager.html", [
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
            "removeList" => $listRemoved,
            "sizeRemoved" => count($listRemoved),
            "sizeAvailable" => count($listAvailable)
        ]);
    }

    public function findUserBySearch(){
        $search = $_GET["search"] ?? "";
        $listUser = User::findAll();

        foreach ($listUser as $key=>$user){
            if (!stripos($user->fullName, $search) && !strpos($user->email, $search) && !strpos($user->userID, $search) && !($search == "")){
                unset($listUser[$key]);
            }
            else {
                unset($user->userPassword);
                $groupName = GroupPermission::find(1, $user->permissionID)->groupName;
                $user->groupName = $groupName;
            }
        }
        echo json_encode($listUser);
    }

    public function getUserId(){
        $userId = $_GET['userId'];
        $user = User::find(1, $userId);
        echo json_encode($user);
    }

    public function getAllGroup(){
        $group = GroupPermission::findAll();
        echo json_encode($group);
    }

    public function deleteUser(){
        $userId = $_POST["userId"];
        $user = User::delete(true, $userId);
    }

    public function saveUser(){
        $user = $this->getUserFromClient();
        $response = [
            "status" => 0,
            "msg" => ""
        ];
        $validateText = $this->validateUser($user);
        if ($validateText == ""){
            try {
                User::save($user);
                $response["status"] = 1;
                $response["msg"] = "Thành công";
            } catch (\Exception $e){
                $response["status"] = 0;
                $response["msg"] = "Bị trùng email";
            }
        }
        else {
            $response["status"] = 0;
            $response["msg"] = $validateText;
        }
        echo json_encode($response);
    }

    public function editUser(){
        $user = $this->getUserFromClient();
        $response = [
            "status" => 0,
            "msg" => ""
        ];
        $validateText = $this->validateUser($user);
        if ($validateText == ""){
            try {
                User::update($user, $user->userID);
                $response["status"] = 1;
                $response["msg"] = "Thành công";
            } catch (\Exception $e){
                $response["status"] = 0;
                $response["msg"] = "Bị trùng email";
            }
         }
        else {
            $response["status"] = 0;
            $response["msg"] = $validateText;
        }
        echo json_encode($response);
    }

    public function validateUser($user):string{
        if ($user->fullName == ""){
            return "Tên người dùng bị trống !";
        }
        if (strlen($user->userPassword) < 5){
            return "Mật khẩu phải hơn 4 kí tự";
        }

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return "Email không đúng định dạng";
        }

        $userGroup = GroupPermission::find(1, $user->permissionID);
        if ($userGroup == null){
            return "Nhóm người dùng không tồn tại";
        }
        return "";
    }

    public function getUserFromClient(){
        $userId = isset($_POST["userId"]) ? (int)$_POST["userId"] : 0;
        $fullName = $_POST["fullName"] ?? "";
        $email = $_POST["email"] ?? "";
        $userPassword = $_POST["userPassword"] ?? "";
        $isActive = $_POST["isActive"] ?? "1";
        $permissionId = $_POST["permission"] ?? "1";
        $user = new User();
        $user->userID = $userId;
        $user->fullName = $fullName;
        $user->email = $email;
        $user->userPassword = $userPassword;
        $user->isActive = $isActive;
        $user->createAt = date("'Y/m/d H:i:s'", time());
        $user->permissionID = $permissionId;
        return $user;
    }

    public function accountRecovery(){
        $accountId = isset($_POST['accountId']) ? (int)$_POST['accountId'] : 0;
        $status = 0;
        $user = User::find(Model::ONLY_DELETED_OBJ, $accountId);
        if ($user != null){
            $user->isDeleted = false;
            User::update($user, $accountId);
            $status = 1;
        }
        echo json_encode(compact("status"));
    }
}