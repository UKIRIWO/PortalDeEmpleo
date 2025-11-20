<?php
require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . '/../rutaLoader.php';

use Controllers\ContactoController;
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
use Controllers\CrearOfertaController;
use Controllers\EliminarOfertaController;
use Controllers\PdfEmpresasController;
use Controllers\SolicitarOfertaController;
use Controllers\SolicitudesController;
use Controllers\MisEstadisticasController;
use Controllers\PoliticaPrivacidadController;
use Controllers\PruebasController;
use Controllers\TerminosUseController;

Session::abrirsesion();

// Proceso el login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'Login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (Login::login($username, $password)) {
        header("Location: Index.php?menu=Inicio");
    } else {
        header("Location: Index.php?menu=Login");
    }
}

//Si no está logeado
if (!Login::estaLogeado()) {
    $menu = $_GET['menu'] ?? 'Inicio';

    switch ($menu) {
        case 'RegistroEmpresa':
            $controller = new RegistroEmpresaController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->procesarRegistroCandidata();
            } else {
                $controller->index();
            }
            break;

        case 'Login':
            $controller = new LoginController();
            $controller->index();
            break;

        case 'Inicio':
            $controller = new InicioController();
            $controller->index();
            break;

        case 'Pruebas':
            $controller = new PruebasController();
            $controller->index();
            break;

        case 'Contacto':
            $controller = new ContactoController();
            $controller->index();
            break;

        case 'TerminosUso':
            $controller = new TerminosUseController();
            $controller->index();
            break;

        case 'PoliticaPrivacidad':
            $controller = new PoliticaPrivacidadController();
            $controller->index();
            break;

        default:
            break;
    }
    //Si está logeado
} else {
    $menu = $_GET['menu'] ?? 'Inicio';

    switch ($menu) {
        case 'Inicio':
            $controller = new InicioController();
            $controller->index();
            break;
        case 'Ofertas':
            $controller = new OfertasController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->procesarOfertas();
            } else {
                $controller->index();
            }
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

        case 'DetallesEmpresa':
            $controller = new DetallesEmpresaController();
            $controller->index();
            break;
        case 'EditarEmpresa':
            $controller = new EditarEmpresaController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->procesarCambios();
            } else {
                $controller->index();
            }
            break;
        case 'EliminarEmpresa':
            $controller = new EliminarEmpresaController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->eliminarEmpresa();
            } else {
                $controller->index();
            }
            break;

        case 'CrearOferta':
            $controller = new CrearOfertaController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->crearOferta();
            } else {
                $controller->index();
            }
            break;

        case 'EliminarOferta':
            $controller = new EliminarOfertaController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->eliminarOferta();
            } else {
                $controller->index();
            }
            break;

        case 'SolicitarOferta':
            $controller = new SolicitarOfertaController();
            $controller->procesarSolicitud();
            break;

        case 'Solicitudes':
            $controller = new SolicitudesController();
            $controller->index();
            break;
        case 'PdfEmpresas':
            $controller = new PdfEmpresasController();
            $controller->generarPdf();
            break;
        case 'MisEstadisticas':
            $controller = new MisEstadisticasController();
            $controller->index();
            break;

        case 'Pruebas':
            $controller = new PruebasController();
            $controller->index();
            break;

        case 'Contacto':
            $controller = new ContactoController();
            $controller->index();
            break;

        case 'TerminosUso':
            $controller = new TerminosUseController();
            $controller->index();
            break;

        case 'PoliticaPrivacidad':
            $controller = new PoliticaPrivacidadController();
            $controller->index();
            break;
        case 'Logout':
            Login::logout();
            header("Location: index.php");
        default:
            $controller = new PageNotFoundController();
            $controller->index();
            break;
    }
}
