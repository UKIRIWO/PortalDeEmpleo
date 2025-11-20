<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

class LoginController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Iniciar sesión'
        ];

        echo $this->templates->render('pages/Login', $data);
    }
}