<?php
namespace app\model;
use core\Model;
//hai primary key nên chưa biết hướng giải quyết
class ShowtimePrice extends Model{
    protected static string $tableName = "showtime_price";
    protected static string $className = "ShowtimePrice";
    protected static string $primaryKey = "";
    protected static bool $isGenerated = false;

    protected int $showID;
    protected int $seatType;
    protected long $ticketPrice;
    protected ?int $discountID;
}