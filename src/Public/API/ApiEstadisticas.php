<?php
header('Content-Type: application/json');

require_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . '/../../Loaders/miAutoLoader.php';

use Helpers\Login;
use Helpers\Session;
use Helpers\Security;
use Repositories\RepoEmpresa;

Session::abrirSesion();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getEstadisticas();
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}

function getEstadisticas()
{
    $statusCode = 200;
    $response = [];

    try {
        Security::verificarToken();

        if (!Login::esEmpresa()) {
            http_response_code(403);
            echo json_encode(["error" => "Solo empresas pueden ver estadísticas"]);
            exit;
        }

        $idUser = Login::getUserId();
        $empresa = RepoEmpresa::findByIdUser($idUser);
        $idEmpresa = $empresa->getId();

        // === CONSULTAS ===
        $totalOfertas       = RepoEmpresa::contarOfertas($idEmpresa);
        $ofertasActivas     = RepoEmpresa::contarOfertasActivas($idEmpresa);
        $totalSolicitudes   = RepoEmpresa::contarSolicitudes($idEmpresa);

        $porEstado          = RepoEmpresa::contarSolicitudesPorEstado($idEmpresa);
        $porOferta          = RepoEmpresa::solicitudesPorOferta($idEmpresa);

        // === FORMATEAR ESTADOS ===
        $labelsEstado = [];
        $dataEstado   = [];
        foreach ($porEstado as $fila) {
            $labelsEstado[] = $fila['estado'];
            $dataEstado[]   = $fila['total'];
        }

        // === FORMATEAR OFERTAS ===
        $labelsOferta = [];
        $dataOferta   = [];
        foreach ($porOferta as $fila) {
            $labelsOferta[] = $fila['titulo'];
            $dataOferta[]   = $fila['total'];
        }

        $response = [
            "totalOfertas" => $totalOfertas,
            "ofertasActivas" => $ofertasActivas,
            "totalSolicitudes" => $totalSolicitudes,
            "solicitudesPorEstado" => [
                "labels" => $labelsEstado,
                "data"   => $dataEstado
            ],
            "solicitudesPorOferta" => [
                "labels" => $labelsOferta,
                "data"   => $dataOferta
            ]
        ];

    } catch (Exception $e) {
        $statusCode = 500;
        $response = [
            "error" => "Error al obtener estadísticas",
            "detalle" => $e->getMessage()
        ];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}
