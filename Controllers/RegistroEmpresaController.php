<?php
include_once "../Loaders/miAutoLoader.php";

class RegistroEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'RegistroEmpresa'
        ];

        echo $this->templates->render('RegistroEmpresa', $data);
    }
}