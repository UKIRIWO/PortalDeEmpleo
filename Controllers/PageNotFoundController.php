<?php
namespace Controllers;
include_once "../Loaders/miAutoLoader.php";

class PageNotFoundController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Página no encontrada'
        ];

        echo $this->templates->render('pages/PageNotFound', $data);
    }
}