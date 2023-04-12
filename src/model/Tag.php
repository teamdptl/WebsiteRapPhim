<?php
namespace app\model;
use core\Model;

class Tag extends Model{
    protected static string $tableName = "tag";
    protected static string $className = "Tag";
    protected static string $primaryKey = "tagID";
    protected static bool $isGenerated = true;

    protected int $tagID;
    protected string $tagName;
    protected int $minAge;
    
}