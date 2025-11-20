<?php
namespace Controllers;
include_once __DIR__ . '/../rutaLoader.php';
use Repositories\RepoEmpresa;
use Repositories\RepoUser;

class DetallesEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = Engine::getEngine();
    }

    public function index() {
        $empresa = RepoEmpresa::findById($_GET['id']);
        $user = RepoUser::findById($empresa->getIdUserFk());
        $data = [
            'titulo' => 'Detalles Empresa',
            'empresa' => $empresa,
            'user' => $user
        ];

        echo $this->templates->render('pages/DetallesEmpresa', $data);
    }
}