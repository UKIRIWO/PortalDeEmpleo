<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';


use Repositories\RepoOferta;
use Repositories\RepoEmpresa;
use Repositories\RepoOfertaCiclo;
use Models\OfertaCiclo;
use Models\Oferta;
use Helpers\Login;

class CrearOfertaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        if (!Login::esEmpresa()) {
            header("Location: index.php?menu=Inicio");
            exit;
        }

        $data = [
            'titulo' => 'Crear Oferta'
        ];

        echo $this->templates->render("pages/CrearOferta", $data);
    }

    public function crearOferta()
    {
        $idEmpresa = RepoEmpresa::findByIdUser(Login::getUserId())->getId();

        $oferta = new Oferta(
            null,
            $idEmpresa,
            $_POST['fecha_inicio'],
            $_POST['fecha_fin'],
            $_POST['titulo'],
            $_POST['descripcion']
        );

        RepoOferta::save($oferta);

        $ofertaCiclo = new OfertaCiclo(
            null,
            $_POST['ciclo'],
            $oferta->getId()
        );

        RepoOfertaCiclo::save($ofertaCiclo);

        header("Location: index.php?menu=Ofertas");
    }
}
