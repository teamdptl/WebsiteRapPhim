<?php
namespace app\controller;
use core\Controller;
use core\View;
use src\model\MovieDetail;

class DetailMovieController extends Controller
{
    public function getDetailMoviePage($id)
    {
        $navbar = GlobalController::getNavbar();
        $movieDetail = self::getMovieDetail();
        View::renderTemplate('detailMovie/detail-movie.html', [
            "navbar" => $navbar,
            "id" => $id,
            "movieDetail" => $movieDetail,
        ]);
    }
    public function getMovieDetail()
    {
        $movieDetail = new \app\model\MovieDetail(
            "Mega man X4",
            "https://m.media-amazon.com/images/W/IMAGERENDERING_521856-T1/images/I/61z1wH3roMS._AC_UF894,1000_QL80_.jpg",
            "13+",
            "Hành Động , Hài Hước",
            "Mega Man X4 là phiên bản thứ 4 trong loạt game hành động Mega Man hay còn gọi là game Rockman nổi tiếng. Người chơi sẽ tiếp tục cuộc phiêu lưu kỳ thú và tiêu diệt tên Sigma độc ác với hai nhân vật quen thuộc là X và Zero."
            ,"Makoto Shinkai","Nanoka Hara, Hokuto Matsumura" ,"2023-03-10","Tiếng việt"
        );
        return $movieDetail;


    }
}

?>