<?php


include_once "../Loaders/miAutoLoader.php";
use League\Plates\Engine;

class LoginController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $data = [
          'titulo' => 'Iniciar sesión'
        ];

        echo $this->templates->render('Login', $data);
    }
}