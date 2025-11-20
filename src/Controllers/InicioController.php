<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

class InicioController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Inicio'
        ];

        echo $this->templates->render('pages/Inicio', $data);
    }
}