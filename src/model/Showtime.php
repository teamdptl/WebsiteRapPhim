<?php

namespace app\model;
use core\Model;

class Showtime extends Model{
    protected static string $tableName = "showtime";
    protected static string $className = "Showtime";
    protected static string $primaryKey = "showID";
    protected static bool $isGenerated = true;

    protected int $showID;
    protected string $timeStart;
    protected int $duringTime;
    protected ?int $roomID;
    protected ?int $movieID;
}