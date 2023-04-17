<?php
namespace app\model;
use core\Model;

class FoodBooing extends Model{
    protected static string $tableName = "food_booking";
    protected static string $className = "FoodBooking";
    protected static $primaryKey = array("bookingID", "foodID");
    protected static bool $isAutoGenerated = true;

    public int $bookingID;
    public int $foodID;
    public long $foodPrice;
    public int $foodUnit;
}