<?php
namespace Controllers;
include_once "../Loaders/miAutoLoader.php";
use Repositories\RepoOferta;
class OfertasController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }
    
    public function index() {
        $ofertas = RepoOferta::findAll() ?? [];
        $data = [
            'titulo' => 'Ofertas de empleo',
            'ofertas' => $ofertas
        ];

        echo $this->templates->render('pages/Ofertas', $data);
    }
}

