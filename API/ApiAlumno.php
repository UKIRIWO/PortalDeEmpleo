<?php
header('Content-Type: application/json');

include_once '../Loaders/miAutoLoader.php';
use Helpers\Session;
use Helpers\Login;
use Repositories\RepoAlumno;
use Repositories\RepoUser;
use Models\Alumno;
use Models\User;
Session::abrirsesion();

if (!Login::estaLogeado() || !Login::esAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            getAlumnos(); //get all
            break;
        case 'POST':
            postAlumno($input); // crear alumno
            break;
        case 'PUT':
            putAlumno($input); //update
            break;
        case 'DELETE':
            deleteAlumno();  //delete 
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function getAlumnos() {
    if (isset($_GET['id'])) {
        $alumno = RepoAlumno::findByIdWithoutCurriculum($_GET['id']);
        if ($alumno) {
            $user = RepoUser::findById($alumno->getIdUserFk());
            //transformar en arrayMap
            echo json_encode([
                'id' => $alumno->getId(),
                'id_user' => $alumno->getIdUserFk(),
                'username' => $user->getNombreUsuario(),
                'dni' => $alumno->getDni(),
                'email' => $alumno->getEmail(),
                'nombre' => $alumno->getNombre(),
                'ape1' => $alumno->getApe1(),
                'ape2' => $alumno->getApe2(),
                'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                'direccion' => $alumno->getDireccion(),
                'foto' => $alumno->getFoto()
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Alumno no encontrado']);
        }
    } else {

        $alumnos = RepoAlumno::findAllWithoutCurriculum();
        $resultado = [];
        
        foreach ($alumnos as $alumno) {
            $user = RepoUser::findById($alumno->getIdUserFk());
            $resultado[] = [
                'id' => $alumno->getId(),
                'id_user' => $alumno->getIdUserFk(),
                'username' => $user ? $user->getNombreUsuario() : '',
                'dni' => $alumno->getDni(),
                'email' => $alumno->getEmail(),
                'nombre' => $alumno->getNombre(),
                'ape1' => $alumno->getApe1(),
                'ape2' => $alumno->getApe2(),
                'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                'direccion' => $alumno->getDireccion(),
                'foto' => $alumno->getFoto()
            ];
        }
        
        echo json_encode($resultado);
    }   
}

function postAlumno($data) {
    // Crear nuevo alumno con usuario
    if (!isset($data['username']) || !isset($data['password']) || !isset($data['dni']) || 
        !isset($data['nombre']) || !isset($data['ape1']) || !isset($data['fecha_nacimiento']) || 
        !isset($data['direccion'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }
    
    // Crear usuario
    $user = new User(
        null,
        $data['username'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        2 // ID rol alumno
    );
    
    // Crear alumno
    $alumno = new Alumno(
        null,
        null,
        $data['dni'],
        $data['nombre'],
        $data['ape1'],
        $data['ape2'] ?? null,
        $data['curriculum'] ?? 'CV pendiente', // Texto temporal si no hay curriculum
        $data['fecha_nacimiento'],
        $data['direccion'],
        $data['foto'] ?? null
    );
    
    // Guardar con transacción
    if (RepoAlumno::save($user, $alumno)) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'id' => $alumno->getId(),
            'message' => 'Alumno creado correctamente'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al crear alumno']);
    }
}

function putAlumno($data) {
    // Actualizar alumno existente
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID no proporcionado']);
        return;
    }
    
    $alumno = RepoAlumno::findById($data['id']);
    if (!$alumno) {
        http_response_code(404);
        echo json_encode(['error' => 'Alumno no encontrado']);
        return;
    }
    
    // Actualizar datos del alumno
    if (isset($data['dni'])) $alumno->setDni($data['dni']);
    if (isset($data['nombre'])) $alumno->setNombre($data['nombre']);
    if (isset($data['ape1'])) $alumno->setApe1($data['ape1']);
    if (isset($data['ape2'])) $alumno->setApe2($data['ape2']);
    if (isset($data['fecha_nacimiento'])) $alumno->setFechaNacimiento($data['fecha_nacimiento']);
    if (isset($data['direccion'])) $alumno->setDireccion($data['direccion']);
    if (isset($data['foto'])) $alumno->setFoto($data['foto']);
    
    RepoAlumno::update($alumno);
    
    // Si hay cambios en el usuario (username o password)
    if (isset($data['username']) || isset($data['password'])) {
        $user = RepoUser::findById($alumno->getIdUserFk());
        if ($user) {
            if (isset($data['username'])) $user->setNombreUsuario($data['username']);
            if (isset($data['password'])) $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
            RepoUser::update($user);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Alumno actualizado correctamente'
    ]);
}

function deleteAlumno() {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID no proporcionado']);
        return;
    }
    
    // Eliminar alumno (y usuario en cascada)
    if (RepoAlumno::delete($_GET['id'])) {
        echo json_encode([
            'success' => true,
            'message' => 'Alumno eliminado correctamente'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar alumno']);
    }
}
?>