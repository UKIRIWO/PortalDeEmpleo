<?php
namespace Controllers;
include_once "../Loaders/miAutoLoader.php";

class EditarEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Editar Empresa'
        ];

        echo $this->templates->render('pages/EditarEmpresa', $data);
    }
}