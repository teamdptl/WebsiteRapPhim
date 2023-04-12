<?php
namespace app\model;
use core\Model;

class Discount extends Model{
    protected static string $tableName = "discount";
    protected static string $className = "Discount";
    protected static string $primaryKey = "discountID";
    protected static bool $isGenerated = true;

    protected int $discountID;
    protected string $discountValue;
    protected string $startTime;
    protected string $endTime;
    protected string $note;
}