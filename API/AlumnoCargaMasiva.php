<?php
namespace API;
header('Content-Type: application/json');

include_once '../Loaders/miAutoLoader.php';

use Helpers\Session;
use Helpers\Login;
use Repositories\RepoAlumno;
use Repositories\RepoUser;
use Repositories\RepoFamilia;
use Repositories\RepoCiclo;
use Repositories\RepoEstudios;
use Models\Alumno;
use Models\User;
use Models\Estudios;

Session::abrirsesion();

if (!Login::estaLogeado() || !Login::esAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGet();
            break;
        case 'POST':
            handlePost();
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}


// GET: Obtener familias y ciclos
function handleGet() {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'familias':
                getFamilias();
                break;
            case 'ciclos':
                getCiclosByFamilia();
                break;
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Acción no válida']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Acción no especificada']);
    }
}


function getFamilias() {
    $familias = RepoFamilia::findAll();
    $resultado = [];
    
    foreach ($familias as $familia) {
        $resultado[] = [
            'id' => $familia->getId(),
            'nombre' => $familia->getNombre()
        ];
    }
    
    echo json_encode($resultado);
}


function getCiclosByFamilia() {
    if (!isset($_GET['familia_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de familia no proporcionado']);
        return;
    }
    
    $familiaId = $_GET['familia_id'];
    $ciclos = RepoCiclo::findByFamilia($familiaId);
    $resultado = [];
    
    foreach ($ciclos as $ciclo) {
        $resultado[] = [
            'id' => $ciclo->getId(),
            'nombre' => $ciclo->getNombre(),
            'nivel' => $ciclo->getNivel()
        ];
    }
    
    echo json_encode($resultado);
}


// POST: Procesar carga masiva
function handlePost() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'No se pudo parsear el JSON']);
            return;
        }
        
        if (!isset($data['alumnos']) || !is_array($data['alumnos'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos de alumnos no proporcionados']);
            return;
        }
        
        if (!isset($data['ciclo_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Ciclo no proporcionado']);
            return;
        }
        
        $cicloId = $data['ciclo_id'];
        $fechaInicio = $data['fecha_inicio'] ?? null;
        $fechaFin = $data['fecha_fin'] ?? null;
        $alumnos = $data['alumnos'];
        
        // Llamar al método de carga masiva
        $resultado = RepoAlumno::saveMassive($alumnos, $cicloId, $fechaInicio, $fechaFin);
        
        // Responder con los resultados
        echo json_encode([
            'success' => true,
            'exitosos' => count($resultado['exitosos']),
            'total' => $resultado['total'],
            'errores' => $resultado['errores'],
            'credenciales' => $resultado['exitosos']
        ]);
        
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
}