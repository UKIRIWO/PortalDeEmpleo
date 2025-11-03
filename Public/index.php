<?php
require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../Loaders/miAutoLoader.php";

Session::abrirsesion();

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

    if ($menu === 'Registro'){
        (new RegistroEmpresaController())->index();
    } else {
        (new LoginController())->index();
    }

} else {
    $menu = $_GET['menu'] ?? 'Inicio';
    
    switch ($menu) {
        case 'Inicio':
            (new InicioController())->index();
            break;
        case 'OfertaAlumno':
            (new OfertaAlumnoController())->index();
            break;
        case 'SolicitudAlumno':
            //(new AlumnoSolicitudController())->index();
            break;
        case 'PanelAdmin':
            //(new PanelAdminController())->index();
            break;
        case 'Logout':
            Login::logout();
            header("Location: index.php");
            exit;
        default:
            echo "Página no encontrada";
            break;
    }
}