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

// Session::abrirsesion();

// if (!Login::estaLogeado() || !Login::esAdmin()) {
//     http_response_code(403);
//     echo json_encode(['error' => 'No autorizado']);
//     exit;
// }

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
    echo json_encode(['error' => $e->getMessage()]);
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
        
        if (!isset($data['alumnos']) || !is_array($data['alumnos'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos de alumnos no proporcionados']);
            return;
        }
        
        if (!isset($data['ciclo_id']) || !isset($data['fecha_inicio'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Ciclo o fecha de inicio no proporcionados']);
            return;
        }
        
        $cicloId = $data['ciclo_id'];
        $fechaInicio = $data['fecha_inicio'];
        $fechaFin = $data['fecha_fin'] ?? null;
        $alumnos = $data['alumnos'];
        
        $exitosos = [];
        $errores = [];
        
        foreach ($alumnos as $alumnoData) {
            try {
                // Validar campos obligatorios
                if (empty($alumnoData['dni']) || empty($alumnoData['nombre']) || 
                    empty($alumnoData['ape1']) || empty($alumnoData['correo'])) {
                    $errores[] = [
                        'alumno' => $alumnoData,
                        'error' => 'Campos obligatorios incompletos (dni, nombre, ape1, correo)'
                    ];
                    continue;
                }
                
                // Generar username y password automáticamente
                $username = strtolower($alumnoData['nombre'] . '.' . $alumnoData['ape1']);
                // Eliminar espacios y caracteres especiales
                $username = preg_replace('/[^a-z0-9.]/', '', $username);
                
                // Password: últimos 3 dígitos DNI + primeras 2 letras nombre en mayúscula
                $ultimosTresDni = substr($alumnoData['dni'], -3);
                $primerasLetrasNombre = strtoupper(substr($alumnoData['nombre'], 0, 2));
                $password = $ultimosTresDni . $primerasLetrasNombre;
                
                // Verificar si el username ya existe, si existe añadir número
                $usernameOriginal = $username;
                $contador = 1;
                while (RepoUser::findByUsername($username) !== null) {
                    $username = $usernameOriginal . $contador;
                    $contador++;
                }
                
                // Crear usuario
                $user = new User(
                    null,
                    $username,
                    password_hash($password, PASSWORD_DEFAULT),
                    3 // rol alumno
                );
                
                // Crear alumno
                $alumno = new Alumno(
                    null,
                    null,
                    $alumnoData['dni'],
                    $alumnoData['correo'],
                    $alumnoData['nombre'],
                    $alumnoData['ape1'],
                    null, //ape2
                    null, // curriculum
                    null, // fecha_nacimiento
                    null, // direccion
                    null  // foto
                );
                
                // Guardar alumno (esto guarda tanto user como alumno)
                if (!RepoAlumno::save($user, $alumno)) {
                    $errores[] = [
                        'alumno' => $alumnoData,
                        'error' => 'Error al guardar en base de datos'
                    ];
                    continue;
                }
                
                // Crear relación con el ciclo en la tabla estudios
                $estudios = new Estudios(
                    null,
                    $alumno->getId(),
                    $cicloId,
                    $fechaInicio,
                    $fechaFin
                );
                
                if (!RepoEstudios::save($estudios)) {
                    // Si falla al guardar estudios, revertir alumno
                    RepoAlumno::delete($alumno->getId());
                    $errores[] = [
                        'alumno' => $alumnoData,
                        'error' => 'Error al vincular con el ciclo'
                    ];
                    continue;
                }
                
                // Éxito
                $exitosos[] = [
                    'alumno' => $alumnoData,
                    'username' => $username,
                    'password' => $password
                ];
                
            } catch (\Exception $e) {
                $errores[] = [
                    'alumno' => $alumnoData,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // Responder con los resultados
        echo json_encode([
            'success' => true,
            'exitosos' => count($exitosos),
            'total' => count($alumnos),
            'errores' => $errores,
            'credenciales' => $exitosos // Para que el admin pueda ver las credenciales generadas
        ]);
        
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

