<?php
namespace app\model;
use core\Model;

class Category extends Model{
    protected static string $tableName = "category";
    protected static string $className = "Category";
    protected static $primaryKey = array("categoryID");
    protected static bool $isAutoGenerated = true;

    public int $categoryID;
    public string $cateName;
        
}