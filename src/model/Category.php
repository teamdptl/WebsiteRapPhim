<?php
namespace app\model;
use core\Model;

class Category extends Model{
    protected static string $tableName = "category";
    protected static string $className = "Category";
    protected static string $primaryKey = "categoryID";
    protected static bool $isGenerated = true;

    protected int $categoryID;
    protected string $cateName;
        
}