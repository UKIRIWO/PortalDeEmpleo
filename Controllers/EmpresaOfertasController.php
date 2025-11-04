<?php
include_once "../Loaders/miAutoLoader.php";

class EmpresaOfertasController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Empresa Ofertas'
        ];

        echo $this->templates->render('pages/EmpresaOfertas', $data);
    }
}