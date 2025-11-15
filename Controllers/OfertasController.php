<?php

namespace Controllers;

include_once "../Loaders/miAutoLoader.php";

use Repositories\RepoOferta;
use Repositories\RepoEmpresa;
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
        $rol = Login::getRol();

        switch ($rol) {
            case 'admin':
                $ofertas = RepoOferta::findAll() ?? [];
                break;
                
            case 'alumno':
                $ofertas = RepoOferta::findByIdUser(Login::getUserId());
                break;

            case 'empresa':
                $ofertas = RepoOferta::findByEmpresaId(RepoEmpresa::findByIdUser(Login::getUserId())->getId());
                break;

            default:
                header("Location: index.php");
                break;
        }

        
        $data = [
            'titulo' => 'Ofertas de empleo',
            'ofertas' => $ofertas
        ];

        echo $this->templates->render('pages/Ofertas', $data);
    }
}
