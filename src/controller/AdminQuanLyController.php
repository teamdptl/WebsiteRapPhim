<?php
namespace app\controller;
use core\Controller;
use core\View;
use core\Request;
use app\model\User;
use app\utils\SessionManager;

class AdminQuanLyController extends Controller{

    public function getAdminQuanLyPhim() {

        // if (Request::$user != null){
        //     Request::redirect("/");
        //     return;
        // }


        $navbar = GlobalController::getNavbar();
        $navAdmin = GlobalController::getNavAdmin();
   
        
        View::renderTemplate('admin/managerFilm_page.html',[
            "navbar" => $navbar,
            "navAdmin"=> $navAdmin,
        ]);
    }

}