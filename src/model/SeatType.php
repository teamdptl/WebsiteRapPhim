<?php
namespace app\model;
use core\Model;

class SeatType extends Model{
    protected static string $tableName = "seat_type";
    protected static string $className = "SeatType";
    protected static string $primaryKey = "seatType";
    protected static bool $isGenerated = true;

    protected int $seatType;
    protected char $seatName;
}