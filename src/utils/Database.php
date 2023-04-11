<?php
namespace app\utils;

use PDO;
use PDOException;

abstract class Database{
    private static string $hostname = "containers-us-west-185.railway.app";
    private static int $port = 7084;
    private static string $username = "root";
    private static string $password = "vIIhZgx1nsfEeDuZd7sF";
    private static string $database = "railway";

    public static function getConnection(): ?PDO {
        $conn = new PDO("mysql:host=".self::$hostname.";dbname=".self::$database.";port=".self::$port, self::$username, self::$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function close($conn){
        $conn = null;
    }
}