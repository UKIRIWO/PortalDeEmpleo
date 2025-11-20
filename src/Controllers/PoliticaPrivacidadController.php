<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

class PoliticaPrivacidadController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Politica de Privacidad'
        ];

        echo $this->templates->render('pages/PoliticaPrivacidad', $data);
    }
}