<?php


include_once "../Loaders/miAutoLoader.php";
use League\Plates\Engine;

class RegistroEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $data = [
          'titulo' => 'RegistroEmpresa'
        ];

        echo $this->templates->render('RegistroEmpresa', $data);
    }
}