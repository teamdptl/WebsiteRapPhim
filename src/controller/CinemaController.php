<?php

namespace app\controller;

use app\model\Cinema;
use app\model\Province;
use core\View;

class CinemaController
{
    public function getCinemaPage(){
        $navbar = GlobalController::getNavbar();
        $provinceId = isset($_GET["provinceId"]) ? (int)$_GET["provinceId"] : 0;
        $provinces = Province::findAll();

        $isAllSelected = false;
        $currentProvince = null;
        if ($provinceId == 0){
            $isAllSelected = true;
        } else {
            foreach ($provinces as $province){
                if ($province->provinceID == $provinceId){
                    $province->select = true;
                    $currentProvince = $province;
                }
            }
        }

        $cinemas = [];
        if ($isAllSelected){
            $cinemas = Cinema::findAll();
        } else {
            if ($currentProvince != null){
                $cinemas = $currentProvince->hasList(Cinema::class);
            }
        }

        View::renderTemplate("cinema/cinema_page.html",[
            "navbar" => $navbar,
            "provinces" => $provinces,
            "cinemas" => $cinemas,
            "isAllSelected" => $isAllSelected,
        ]);
    }
}