<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';

use Helpers\Login;
use Repositories\RepoAlumno;
use Repositories\RepoEmpresa;
use Repositories\RepoSolicitud;

class SolicitudesController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $solicitudes = [];
        $idUser = Login::getUserId();
        $rol = Login::getRol();
        
        switch ($rol) {
            case "admin": // puede ver todas las solicitudes
                $solicitudes = RepoSolicitud::findAllAdmin(); // Necesitas crear este método
                break;

            case "empresa": // ve todas las solicitudes recibidas de sus ofertas
                $empresa = RepoEmpresa::findByIdUser($idUser);
                if ($empresa) {
                    $solicitudes = RepoSolicitud::findByEmpresaId($empresa->getId());
                }
                break;

            case "alumno": // puede ver todas sus solicitudes mandadas 
                $alumno = RepoAlumno::findByIdUser($idUser);
                if ($alumno) {
                    $solicitudes = RepoSolicitud::findByAlumnoId($alumno->getId());
                }
                break;

            default:
                break;
        }

        $data = [
            'titulo' => 'Solicitudes',
            'solicitudes' => $solicitudes
        ];

        echo $this->templates->render('pages/Solicitudes', $data);
    }
}