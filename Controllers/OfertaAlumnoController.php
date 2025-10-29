<?php
include_once "../Loaders/miAutoLoader.php";
use League\Plates\Engine;

class OfertaAlumnoController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $data = [
            'titulo' => 'Ofertas de empleo',
            'mensaje' => 'Bienvenido a las ofertas activas'
        ];

        echo $this->templates->render('OfertaAlumno', $data);
    }
}