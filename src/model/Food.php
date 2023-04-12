<?php
namespace app\model;
use core\Model;

class Food extends Model{
    protected static string $tableName = "food";
    protected static string $className = "Food";
    protected static string $primaryKey = "foodID";
    protected static bool $isGenerated = true;

    protected int $foodID;
    protected string $foodImage;
    protected string $foodName;
    protected long $foodPrice;
    protected string $foodDescription;
    protected ?int $discountID;
}