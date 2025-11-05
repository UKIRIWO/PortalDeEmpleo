<?php
namespace Controllers;
use Repositories\RepoSolicitud;
include_once "../Loaders/miAutoLoader.php";

class EmpresaSolicitudesController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $solicitudes = RepoSolicitud::findAll() ?? [];
        $data = [
            'titulo' => 'Empresa Solicitudes',
            'solicitudes' => $solicitudes
        ];

        echo $this->templates->render('pages/EmpresaSolicitudes', $data);
    }
}