<?php
namespace app\model;
use core\Model;

class Room extends Model{
    protected static string $tableName = "room";
    protected static string $className = "Room";
    protected static string $primaryKey = "roomID";
    protected static bool $isGenerated = true;

    protected int $roomID;
    protected string $roomName;
    protected int $numberOfRow;
    protected int $numberOfCol;
    protected ?int $cinemaID;
}