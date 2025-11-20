<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';

use Repositories\RepoOferta;

class EliminarOfertaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        $idOferta = $_GET['id'];

        $oferta = RepoOferta::findById($idOferta);

        $data = [
            'titulo' => 'Eliminar Oferta',
            'oferta' => $oferta
        ];

        echo $this->templates->render("pages/EliminarOferta", $data);
    }

    public function eliminarOferta()
    {
        try {
            $idOferta = $_POST['id_oferta'] ?? null;

            if (!$idOferta) {
                throw new \Exception("No se recibió el ID de la oferta.");
            }

            RepoOferta::delete($idOferta);

            header("Location: index.php?menu=Ofertas");
            exit;

        } catch (\Exception $e) {
            error_log("Error en EliminarOfertaController::eliminarOferta(): " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error al eliminar la oferta.";
            header("Location: index.php?menu=Ofertas");
            exit;
        }
    }
}
