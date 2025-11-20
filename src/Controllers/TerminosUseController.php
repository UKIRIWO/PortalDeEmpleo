<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

class TerminosUseController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Terminos de Uso'
        ];

        echo $this->templates->render('pages/TerminosUso', $data);
    }
}