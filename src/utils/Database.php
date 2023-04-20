<?php
namespace app\utils;

use PDO;
use PDOException;

abstract class Database{
    // private static string $hostname = "containers-us-west-185.railway.app";
    // private static int $port = 7084;
    // private static string $username = "root";
    // private static string $password = "vIIhZgx1nsfEeDuZd7sF";
    // private static string $database = "railway";

    private static string $hostname = "127.0.0.1";
    private static int $port = 3306;
    private static string $username = "root";
    private static string $password = "";
    private static string $database = "MOVIE_BOOKING";

    public static function getConnection(): ?PDO {
        $conn = new PDO("mysql:host=".self::$hostname.";dbname=".self::$database.";port=".self::$port, self::$username, self::$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function close($conn){
        $conn = null;
    }

    public static function runQuery($query){
        $conn = Database::getConnection();
        $isRun = $conn->query($query);
        if ($isRun == false){
            echo "(x) Run failed query: $query";
        }
        else {
            echo "(v) Run successfully query: $query";
        }
        $conn = null;
    }
}