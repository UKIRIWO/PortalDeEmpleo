<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';

use Repositories\RepoEmpresa;

class EliminarEmpresaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        $empresa = RepoEmpresa::findById($_GET['id']);
        $data = [
            'titulo' => 'Eliminar Empresa',
            'empresa' => $empresa
        ];

        echo $this->templates->render('pages/EliminarEmpresa', $data);
    }

    public function eliminarEmpresa()
    {
        try {
            $idEmpresa = $_POST['id_empresa'] ?? null;

            if (!$idEmpresa) {
                throw new \Exception("No se recibió el ID de la empresa.");
            }

            RepoEmpresa::delete($idEmpresa);

            header("Location: index.php?menu=PanelAdmin");
            exit;
        } catch (\Exception $e) {
            error_log("Error en ConfirmarEliminarEmpresaController::eliminarEmpresa(): " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error al eliminar la empresa.";
            header("Location: index.php?menu=PanelAdmin");
            exit;
        }
    }
}
