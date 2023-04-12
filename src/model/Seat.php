<?php
namespace app\model;
use core\Model;

class Seat extends Model{
    protected static string $tableName = "seat";
    protected static string $className = "Seat";
    protected static string $primaryKey = "seatID";
    protected static bool $isGenerated = true;

    protected int $seatID;
    protected char $seatRow;
    protected int $seatCol;
    protected ?int $eatType;
    protected ?int $roomID;
}