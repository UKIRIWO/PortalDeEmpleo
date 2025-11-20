<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

class ContactoController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Contacto'
        ];

        echo $this->templates->render('pages/Contacto', $data);
    }
}