<?php
require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../Loaders/miAutoLoader.php";


Session::abrirsesion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion']) == 'Login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (Login::login($username, $password)) {
        header("Location: Index.php?menu=Inicio");
        exit;
    }
}

if (!Login::estaLogeado()) {
    $menu = $_GET['menu'] ?? 'Login';

    if ($menu === 'Registro'){
        //registro
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
        case 'Login':
            (new LoginController())->index();
            break;
        case 'Logout':
            Login::logout();
            header("Location: index.php");
            break;
        default:
            echo "Página no encontrada";
            break;
    }
}
