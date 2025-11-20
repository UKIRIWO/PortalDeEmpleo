<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';

use Repositories\RepoEmpresa;
use Helpers\Login;
use Services\MailService;

class PanelAdminController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        if (!Login::esAdmin()) {
            header("Location: index.php?menu=Inicio");
            exit;
        }
        // Si página o tamaño no existen o son negativos se les da valores por defecto
        if (isset($_GET['page'])) {
            $valorPage = (int)$_GET['page'];
            if ($valorPage > 1) {
                $page = $valorPage;
            } else {
                $page = 1;
            }
        } else {
            $page = 1;
        }

        if (isset($_GET['size'])) {
            $valorsize = (int)$_GET['size'];
            if ($valorsize > 1) {
                $size = $valorsize;
            } else {
                $size = 5;
            }
        } else {
            $size = 5;
        }

        $busqueda = $_GET['busqueda'] ?? '';

        $offset = ($page - 1) * $size;

        // Obtener empresas según si hay búsqueda o no
        if (!empty($busqueda)) {
            $empresas = RepoEmpresa::findByNombrePaginated($busqueda, $size, $offset);
            $totalEmpresas = RepoEmpresa::countByNombre($busqueda);
        } else {
            $empresas = RepoEmpresa::findAllPaginated($size, $offset);
            $totalEmpresas = RepoEmpresa::countAll();
        }

        // Dividimos las empresas etre las páginas y redondeamos al mayor
        $totalPaginas = ceil($totalEmpresas / $size);

        $empresasC = RepoEmpresa::findAllCandidata();

        $data = [
            'titulo' => 'Panel Admin',
            'empresas' => $empresas,
            'empresasC' => $empresasC,
            'paginaActual' => $page,
            'size' => $size,
            'totalPaginas' => $totalPaginas,
            'busqueda' => $busqueda
        ];

        echo $this->templates->render('pages/PanelAdmin', $data);
    }

    public function procesarPanelAdmin()
    {
        if (!Login::esAdmin()) {
            header("Location: index.php?menu=Inicio");
            exit;
        }

        // cojo la id de la empresa y la accion (detalles, editar, eliminar, aprobar o rechazar)
        $accion = $_POST['accion'] ?? null;
        $idEmpresa = $_POST['id_empresa'] ?? null;
        $idEmpresaC = $_POST['id_empresaC'] ?? null;


        if (!$accion || (!$idEmpresa && !$idEmpresaC)) {
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
                $this->procesarAprobacion($idEmpresaC);
                break;

            case 'rechazar':
                $this->procesarRechazo($idEmpresaC);
                break;

            default:
                header("Location: index.php?menu=PanelAdmin");
                break;
        }
        exit;
    }

    private function procesarAprobacion($idEmpresaC)
    {
        // Busco la empresa
        $empresa = RepoEmpresa::findByIdCandidata($idEmpresaC);

        if ($empresa) {
            // La apruebo
            RepoEmpresa::aprobarCandidata($idEmpresaC);

            // Le envio un correo de aprobación
            $mailService = new MailService();
            $mailService->enviarCorreoAprobacion(
                $empresa->getCorreoDeContacto(),
                $empresa->getNombre()
            );
        }

        header("Location: index.php?menu=PanelAdmin");
    }

    private function procesarRechazo($idEmpresaC)
    {
        // Busco la empresa
        $empresa = RepoEmpresa::findByIdCandidata($idEmpresaC);

        if ($empresa) {
            // Envio un correo de rechazo antes de eliminar para poder hacer los get
            $mailService = new MailService();
            $mailService->enviarCorreoRechazo(
                $empresa->getCorreoDeContacto(),
                $empresa->getNombre()
            );

            // Elimino la empresa candidata
            RepoEmpresa::deleteCandidata($idEmpresaC);
        }

        header("Location: index.php?menu=PanelAdmin");
    }
}
