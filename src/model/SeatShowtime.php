<?php
namespace app\model;
use core\Model;

//2 primary key
class SeatShowtime extends Model{
    protected static string $tableName = "seat_showtime";
    protected static string $className = "SeatShowTime";
    protected static string $primaryKey = "";
    protected static bool $isGenerated = false;

    protected int $showID;
    protected int $seatID;
    protected string $pickedAt;
    protected long $seatPrice;
    protected bool $isBooked;
    protected ?int $userID;
    protected ?int $bookingID;
}