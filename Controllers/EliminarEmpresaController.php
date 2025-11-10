<?php
namespace Controllers;
include_once "../Loaders/miAutoLoader.php";

class EliminarEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Eliminar Empresa'
        ];

        echo $this->templates->render('pages/EliminarEmpresa', $data);
    }
}