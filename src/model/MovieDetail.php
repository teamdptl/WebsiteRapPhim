<?php

namespace app\model;
use core\Model;

class MovieDetail extends Model
{
    public $name;
    public $linkImage;
    public $age;
    public $type;
    public $content;
    public  $director ;

    public $actor;

    public $time ;

    public $language;


    function __construct($name, $linkImage, $age, $type, $content,$director,$actor,$time,$language)
    {
        $this->name = $name;
        $this->linkImage = $linkImage;
        $this->age = $age;
        $this->type = $type;
        $this->content = $content;
        $this->director = $director;
        $this->actor = $actor;
        $this->time = $time;
        $this->language = $language;
    }
}
?>