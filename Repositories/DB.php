<?php

class DB {

    private static $con = null;
    private static $host = 'localhost';
    private static $user = 'root';
    private static $pass = 'root';
    private static $db   = 'portal_de_empleo';


    public static function getConnection() {
        if (self::$con === null) {
            self::$con = new PDO(
                'mysql:host=' . self::$host .
                ';dbname=' . self::$db .
                ';charset=utf8mb4', self::$user, self::$pass
            );
        }
        return self::$con;
    }
}

