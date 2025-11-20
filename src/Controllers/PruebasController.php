<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

class PruebasController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Pruebas'
        ];

        echo $this->templates->render('pages/Pruebas', $data);
    }
}