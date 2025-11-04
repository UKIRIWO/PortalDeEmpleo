<?php
include_once "../Loaders/miAutoLoader.php";

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