<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';

use Repositories\RepoOferta;
use Repositories\RepoEmpresa;
use Repositories\RepoAlumno;
use Helpers\Login;

class OfertasController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        $data = [];
        $idUser = Login::getUserId();
        $rol = Login::getRol();

        
        //segun el rol se pintaran las ofertas
        switch ($rol) {
            case 'admin':
                $ofertas = RepoOferta::findAll() ?? [];
                break;

            case 'alumno':
                $idAlumno = RepoAlumno::findByIdUser($idUser)->getId();
                $ofertas = RepoOferta::findByIdUser($idUser);
                break;

            case 'empresa':
                $ofertas = RepoOferta::findByIdUser($idUser);
                break;

            default:
                header("Location: index.php");
                break;
        }


        //si eres alumno y solicitad hace falta tu id para solicitar
        //si eres empresa/admin no hace falta el id para eliminar, solo hace falta el id de la oferta
        if ($rol === "alumno") {
            $data = [
                'titulo' => 'Ofertas de empleo',
                'ofertas'   => $ofertas,
                'rol'       => $rol,
                'idAlumno'  => $idAlumno
            ];
        } else {
            $data = [
                'titulo' => 'Ofertas de empleo',
                'ofertas'   => $ofertas,
                'rol'       => $rol
            ];
        }

        echo $this->templates->render('pages/Ofertas', $data);
    }



    public function procesarOfertas()
    {
        
        $accion = $_POST['accion'] ?? null;
        $idOferta = $_POST['id_oferta'] ?? null;

        if (!$accion || !$idOferta) {
            header("Location: index.php?menu=Ofertas");
            exit;
        }

        switch ($accion) {

            case 'eliminar':
                header("Location: index.php?menu=EliminarOferta&id=$idOferta");
                break;

            case 'solicitar':
                header("Location: index.php?menu=SolicitarOferta&id=$idOferta");
                break;

            default:
                header("Location: index.php?menu=Ofertas");
                break;
        }

        exit;
    }
}
