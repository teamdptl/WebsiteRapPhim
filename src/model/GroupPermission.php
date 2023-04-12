<?php
namespace app\model;
use core\Model;

class GroupPermission extends Model{
    protected static string $tableName = "group_permission";
    protected static string $className = "GroupPermission";
    protected static string $primaryKey = "permissionID";
    protected static bool $isGenerated = true;

    protected int $permissionID;
    protected string $groupName;
    
}