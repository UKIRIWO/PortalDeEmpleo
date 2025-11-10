<?php

namespace Controllers;

include_once "../Loaders/miAutoLoader.php";

use Repositories\RepoEmpresa;
use Helpers\Login;



class PanelAdminController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        $empresas = RepoEmpresa::findAll();
        $empresasC = RepoEmpresa::findAllCandidata();
        $data = [
            'titulo' => 'Panel Admin',
            'empresas' => $empresas,
            'empresasC' => $empresasC
        ];

        echo $this->templates->render('pages/PanelAdmin', $data);
    }

    public function procesarPanelAdmin()
    {
        if (!Login::esAdmin()) {
            header("Location: index.php?menu=Inicio");
            exit;
        }

        $accion = $_POST['accion'] ?? null;
        $idEmpresa = $_POST['id_empresa'] ?? null;

        error_log("accion: ". $accion);

        if (!$accion || !$idEmpresa) {
            header("Location: index.php?menu=PanelAdmin");
            exit;
        }

        switch ($accion) {
            case 'detalles':
                header("Location: index.php?menu=DetallesEmpresa&id=$idEmpresa");
                break;

            case 'editar':
                header("Location: index.php?menu=EditarEmpresa&id=$idEmpresa");
                break;

            case 'eliminar':
                header("Location: index.php?menu=EliminarEmpresa&id=$idEmpresa");
                break;

            case 'aprobar':
                RepoEmpresa::aprobarCandidata($idEmpresa);
                header("Location: index.php?menu=PanelAdmin");
                break;

            case 'rechazar':
                RepoEmpresa::deleteCandidata($idEmpresa);
                header("Location: index.php?menu=PanelAdmin");
                break;

            default:
                header("Location: index.php?menu=PanelAdmin");
                break;
        }
        exit;
    }
}
