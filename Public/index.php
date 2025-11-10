<?php
require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../Loaders/miAutoLoader.php";

use Controllers\DetallesEmpresaController;
use Controllers\EditarEmpresaController;
use Controllers\EliminarEmpresaController;
use Helpers\Session;
use Helpers\Login;
use Controllers\LoginController;
use Controllers\RegistroEmpresaController;
use Controllers\InicioController;
use Controllers\OfertasController;
use Controllers\PanelAdminController;
use Controllers\PageNotFoundController;

Session::abrirsesion();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'Login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (Login::login($username, $password)) {
        header("Location: Index.php?menu=Inicio");
        exit;
    } else {
        header("Location: Index.php?menu=Login");
        exit;
    }
}

if (!Login::estaLogeado()) {
    $menu = $_GET['menu'] ?? 'Login';

    if ($menu === 'RegistroEmpresa') {
        $controller = new RegistroEmpresaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->procesarRegistroCandidata();
        } else {
            $controller->index();
        }
    } else {
        (new LoginController())->index();
    }
} else {
    $menu = $_GET['menu'] ?? 'Inicio';

    switch ($menu) {
        case 'Inicio':
            (new InicioController())->index();
            break;
        case 'Ofertas':
            (new OfertasController())->index();
            break;
        case 'PanelAdmin':
            $controller = new PanelAdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->procesarPanelAdmin();
            } else {
                $controller->index();
            }
            break;
        case 'RegistroEmpresa':
            $controller = new RegistroEmpresaController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->procesarRegistroEmpresa();
            } else {
                $controller->index();
            }
            exit;

        case 'DetallesEmpresa':
            (new DetallesEmpresaController())->index();
            break;
        case 'EditarEmpresa':
            (new EditarEmpresaController())->index();
            break;
        case 'EliminarEmpresa':
            (new EliminarEmpresaController())->index();
            break;
        case 'Logout':
            Login::logout();
            header("Location: index.php");
            exit;
        default:
            (new PageNotFoundController())->index();
            break;
    }
}
