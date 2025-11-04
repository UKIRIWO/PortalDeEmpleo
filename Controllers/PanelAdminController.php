<?php
include_once "../Loaders/miAutoLoader.php";

class PanelAdminController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Panel Admin'
        ];

        echo $this->templates->render('PanelAdmin', $data);
    }
}