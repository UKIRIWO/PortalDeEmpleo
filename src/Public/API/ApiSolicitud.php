<?php
namespace API;

header('Content-Type: application/json');
include_once __DIR__ . '/../../Loaders/miAutoLoader.php';

use Helpers\Session;
use Helpers\Login;
use Helpers\Security;
use Repositories\RepoSolicitud;

Session::abrirsesion();

if (!Login::estaLogeado()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            getSolicitudes(); //solo sirve para verificar el token cuando cargo la tabla
            break;
        case 'PUT':
            updateSolicitud();
            break;
        case 'DELETE':
            deleteSolicitud();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function getSolicitudes() {
    Security::verificarToken();
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Usar el controlador PHP para cargar solicitudes']);
}

function updateSolicitud() {
    Security::verificarToken();
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
        return;
    }

    $id = $data['id'];

    // Actualizo el estado (aceptar/rechazar) - SOLO EMPRESA
    if (isset($data['estado'])) {
        if (!Login::esEmpresa() && !Login::esAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'No autorizado para esta acción']);
            return;
        }
        
        if (RepoSolicitud::updateEstado($id, $data['estado'])) {
            echo json_encode(['success' => true, 'mensaje' => 'Estado actualizado']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error al actualizar estado']);
        }
        return;
    }

    // Actualizar favorito - SOLO ALUMNO
    if (isset($data['favorito'])) {
        if (!Login::esAlumno()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'No autorizado para esta acción']);
            return;
        }
        
        if (RepoSolicitud::updateFavorito($id, $data['favorito'])) {
            echo json_encode(['success' => true, 'mensaje' => 'Favorito actualizado']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error al actualizar favorito']);
        }
        return;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
}

function deleteSolicitud() {
    Security::verificarToken();
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
        return;
    }

    // Solo alumnos pueden eliminar sus solicitudes
    if (!Login::esAlumno()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        return;
    }

    if (RepoSolicitud::delete($data['id'])) {
        echo json_encode(['success' => true, 'mensaje' => 'Solicitud eliminada']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al eliminar solicitud']);
    }
}
?>