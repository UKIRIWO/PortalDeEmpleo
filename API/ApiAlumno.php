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
            postAlumno(); // crear alumno
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







function getAlumnos()
{
    if (isset($_GET['id'])) {
        $alumno = RepoAlumno::findByIdWithoutCurriculum($_GET['id']);
        if ($alumno) {
            $user = RepoUser::findById($alumno->getIdUserFk());
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
                'username' => $user->getNombreUsuario(),
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













function postAlumno()
{
    try {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $dni = $_POST['dni'] ?? null;
    $email = $_POST['email'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $ape1 = $_POST['ape1'] ?? null;
    $ape2 = $_POST['ape2'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $direccion = $_POST['direccion'] ?? null;

    // Validación de campos obligatorios
    if (!$dni || !$email || !$nombre || !$ape1 || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos: dni, email, nombre, ape1 y password son obligatorios']);
        return;
    }

    $user = new User(
        null,
        $username,
        password_hash($password, PASSWORD_DEFAULT),
        3
    );

    $alumno = new Alumno(
        null,
        null,
        $dni,
        $email,
        $nombre,
        $ape1,
        $ape2,
        null, // curriculum 
        $fecha_nacimiento,
        $direccion,
        null // foto
    );

    if (!RepoAlumno::save($user, $alumno)) {
        // http_response_code(500);
        // echo json_encode(['error' => 'Error al crear alumno en la base de datos']);
        // return;
        throw new Exception('Error al guardar en BD');
    }

    // Procesar curriculum (BLOB)
    if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
        $curriculumContent = file_get_contents($_FILES['curriculum']['tmp_name']);
        $alumno->setCurriculum($curriculumContent);
    }

    // Procesar foto de perfil (archivo en servidor)
    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
        $rutaFoto = "foto_" . $alumno->getIdUserFk() . "." . $extension;

        if (!file_exists("../.imagenes/alumno/")) {
            mkdir("../.imagenes/alumno/", 0777, true);
        }

        if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], "../.imagenes/alumno/" . $rutaFoto)) {
            $alumno->setFoto($rutaFoto);
        }
    }

    RepoAlumno::update($alumno);

    http_response_code(201);
        echo json_encode([
            'success' => true,
            'mensaje' => 'Alumno creado correctamente',
            'id' => $alumno->getId()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}










function putAlumno($data)
{
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














function deleteAlumno()
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID no proporcionado']);
        return;
    }

    // Eliminar alumno (y usuario en cascada)
    if (RepoAlumno::delete($input['id'])) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Alumno eliminado correctamente'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar alumno']);
    }
}
