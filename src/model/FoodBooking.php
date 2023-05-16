<?php
namespace app\model;
use core\Model;

class FoodBooking extends Model{
    protected static string $tableName = "food_booking";
    protected static string $className = "FoodBooking";
    protected static $primaryKey = array("bookingID", "foodID");
    protected static bool $isAutoGenerated = true;

    public int $bookingID = 0;
    public int $foodID = 0;
    public int $foodPrice = 0;
    public int $foodUnit = 0;
}