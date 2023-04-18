<?php

namespace app\model;
use core\Model;

class Cinema extends Model{
    protected static string $tableName = "cinema";
    protected static string $className = "Cinema";
    protected static $primaryKey = array("cinemaID");
    protected static bool $isAutoGenerated = true;

    public int $cinemaID;
    public string $cinemaName;
    public string $cinemaAddress;
    public string $mapLink;
    public ?int $provinceID;
}