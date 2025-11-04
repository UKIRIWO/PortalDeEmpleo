<?php
include_once "../Loaders/miAutoLoader.php";

class AlumnoSolicitudController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $data = [
            'titulo' => 'Alumno Solicitud'
        ];

        echo $this->templates->render('pages/AlumnoSolicitud', $data);
    }
}