<?php
namespace Controllers;
include_once "../Loaders/miAutoLoader.php";
use Repositories\RepoEmpresa;
class PanelAdminController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $empresas=RepoEmpresa::findAll();
        $empresasC=RepoEmpresa::findAllCandidata();
        $data = [
            'titulo' => 'Panel Admin',
            'empresas' => $empresas,
            'empresasC' => $empresasC
        ];

        echo $this->templates->render('pages/PanelAdmin', $data);
    }
}