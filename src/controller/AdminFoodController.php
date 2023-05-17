<?php
namespace app\controller;

use app\model\Food;
use core\Controller;
use core\Model;
use core\View;

class AdminFoodController extends Controller
{
    public function getAdminFoodPage()
    {

        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
        $foodList = Food::findAll();
        View::renderTemplate('admin/adminFood_page.html', [
            "navbar" => $navbar,
            "navAdmin" => $navAdmin,
            "foodList" => $foodList,

        ]);
    }
    public function insertFood()
    {
        $food = new Food();
        $food->foodName = $_POST["foodName"];
        $food->foodImage = $_POST["image"];
        $food->foodPrice = $_POST["price"];
        $food->discountID =1;
        $food->foodDescription = $_POST["descrip"];
        $food->isDeleted = 0;
        Food::save($food);
        $message = [];
        $message["message"] = "Thêm thành công";
        $message["status"] = "Dữ liệu đã được thêm vào cơ sở dữ liệu";
        $message["type"] = "success";
        $json = json_encode($message);
        echo $json;
        exit();
    }
    public function editFood()
    {
        $food = Food::find(Model::UN_DELETED_OBJ, $_GET["foodID"]);
        $json = json_encode($food);
        echo $json;
    }
    public function updateFood()
    {
        $food = new Food();
        $foodID = $_POST["foodID"];
        $food->foodName = $_POST["foodName"];
        if ($_POST["image"] == "") {
            $foodFind = Food::find(Model::UN_DELETED_OBJ, $foodID);
            $food->foodImage = $foodFind->foodImage;
        } else {
            $food->foodImage = $_POST["image"];
        }
        $food->foodPrice = $_POST["price"];
        $food->discountID = 1;
        $food->foodDescription = $_POST["descrip"];
        $food->isDeleted = 0;
        Food::update($food, $foodID);
        $message = [];
        $message["message"] = "Thêm thành công";
        $message["status"] = "Dữ liệu đã được thêm vào cơ sở dữ liệu";
        $message["type"] = "success";
        $json = json_encode($message);
        echo $json;
        exit();
    }
    public function delFood(){
        $foodId = $_POST["foodID"];
        Food::delete(true, $foodId);
        $message = [];
        $message["message"] = "Xóa thành công";
        $message["status"] = "Dữ liệu đã được xóa trong cơ sở dữ liệu";
        $message["type"] = "success";
        $json = json_encode($message);
        echo $json;
        exit();

    }
}



?>