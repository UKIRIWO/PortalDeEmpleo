<?php
namespace Controllers;

use Helpers\Login;
use Repositories\RepoEmpresa;

include_once __DIR__ . '/../rutaLoader.php';

class MisEstadisticasController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        if(!Login::esEmpresa()){
            header("Location: index.php?menu=noTonto");
        }
        
        $data = [
            'titulo' => 'Mis Estadísticas'
        ];

        echo $this->templates->render('pages/MisEstadisticas', $data);
    }
}