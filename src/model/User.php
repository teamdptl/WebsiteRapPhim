<?php

namespace app\model;
use core\Model;

class User extends Model
{
    protected static string $tableName = "User";
    protected static string $className = "User";
    protected static string $primaryKey = "userID";
}