<?php
namespace Controllers;
use League\Plates\Engine as PlatesEngine;

class Engine {

    private static $engine = null;

    public static function getEngine() {
        if (self::$engine === null) {
            self::$engine = new PlatesEngine(__DIR__ . '/../Views');
        }
        return self::$engine;
    }
}
