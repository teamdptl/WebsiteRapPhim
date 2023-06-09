<?php
namespace app\model;
use core\Model;

class Discount extends Model{
    protected static string $tableName = "discount";
    protected static string $className = "Discount";
    protected static $primaryKey = array("discountID");
    protected static bool $isAutoGenerated = true;

    public int $discountID;
    public string $discountValue;
    public string $startTime;
    public string $endTime;
    public string $note;
    public bool $isDeleted;
}