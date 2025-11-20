<?php
namespace Repositories;
class DB {

    private static $con = null;
    private static $host = 'mysql';
    private static $user = 'root';
    private static $pass = 'root';
    private static $db   = 'portal_de_empleo';


    public static function getConnection() {
        if (self::$con === null) {
            self::$con = new \PDO(
                'mysql:host=' . self::$host .
                ';dbname=' . self::$db .
                ';charset=utf8mb4', self::$user, self::$pass
            );
        }
        return self::$con;
    }

    public static function cantidadVariables($cantidad)
    {
        $resultado = "?";
        for ($i = 1; $i < $cantidad; $i++) {
            $resultado = $resultado . ", ?";
        }
        return $resultado;
    }
}

