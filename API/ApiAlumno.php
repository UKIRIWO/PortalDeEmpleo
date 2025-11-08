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


try {
    switch ($method) {
        case 'GET':
            getAlumnos(); //get all
            break;
        case 'POST':
            postAlumno(); // crear alumno
            break;
        case 'PUT':
            putAlumno(); //update
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
                'password' => $user->getPassword(),
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










function putAlumno()
{
    try {
        // Obtener datos del JSON
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = $data['id'];

        // Buscar alumno
        $alumno = RepoAlumno::findById($id);
        if (!$alumno) {
            http_response_code(404);
            echo json_encode(['error' => 'Alumno no encontrado']);
            return;
        }

        $idUser = $alumno->getIdUserFk();

        // Actualizar datos básicos del alumno
        if (isset($data['dni'])) $alumno->setDni($data['dni']);
        if (isset($data['nombre'])) $alumno->setNombre($data['nombre']);
        if (isset($data['ape1'])) $alumno->setApe1($data['ape1']);
        if (isset($data['ape2'])) $alumno->setApe2($data['ape2']);
        if (isset($data['email'])) $alumno->setEmail($data['email']);
        if (isset($data['fecha_nacimiento'])) $alumno->setFechaNacimiento($data['fecha_nacimiento']);
        if (isset($data['direccion'])) $alumno->setDireccion($data['direccion']);

        // Procesar curriculum (Base64 a BLOB)
        if (isset($data['curriculum']) && !empty($data['curriculum'])) {
            // El formato es: "data:application/pdf;base64,JVBERi0xLjQK..."
            // Separar el contenido Base64 del prefijo
            $parts = explode(',', $data['curriculum']);
            
            if (count($parts) === 2) {
                $curriculumBase64 = $parts[1];
                $curriculumContent = base64_decode($curriculumBase64);
                
                if ($curriculumContent !== false) {
                    $alumno->setCurriculum($curriculumContent);
                }
            }
        }

        // Procesar foto (Base64 a archivo en servidor)
        if (isset($data['foto']) && !empty($data['foto'])) {
            // El formato es: "data:image/png;base64,iVBORw0KGgo..."
            $parts = explode(',', $data['foto']);
            
            if (count($parts) === 2) {
                $fotoBase64 = $parts[1];
                $fotoContent = base64_decode($fotoBase64);
                
                if ($fotoContent !== false) {
                    $rutaFoto = "foto_" . $idUser . ".png";
                    
                    // Crear carpeta si no existe
                    if (!file_exists("../.imagenes/alumno/")) {
                        mkdir("../.imagenes/alumno/", 0777, true);
                    }
                    
                    // Guardar archivo en servidor
                    if (file_put_contents("../.imagenes/alumno/" . $rutaFoto, $fotoContent) !== false) {
                        // Solo actualizar BD si era null antes
                        if ($alumno->getFoto() === null) {
                            $alumno->setFoto($rutaFoto);
                        }
                    }
                }
            }
        }

        // Actualizar alumno en BD
        RepoAlumno::update($alumno);

        // Actualizar usuario (username y/o password)
        if (isset($data['username']) || isset($data['password'])) {
            $user = RepoUser::findById($idUser);
            if ($user) {
                if (isset($data['username'])) {
                    $user->setNombreUsuario($data['username']);
                }
                if (isset($data['password']) && !empty($data['password'])) {
                    $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
                }
                RepoUser::update($user);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Alumno actualizado correctamente'
        ]);

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            http_response_code(409);
            echo json_encode(['error' => 'Ya existe un registro con estos datos (DNI o username duplicado)']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}














function deleteAlumno()
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID no proporcionado']);
        return;
    }

    $idAlumno = $input['id'];


    $alumno = RepoAlumno::findByIdWithoutCurriculum($idAlumno);

    if (!$alumno) {
        http_response_code(404);
        echo json_encode(['error' => 'Alumno no encontrado']);
        return;
    }


    if (RepoAlumno::delete($idAlumno)) {


        $idUser = $alumno->getIdUserFk();
        $rutaFoto = __DIR__ . '/../.imagenes/alumno/foto_' . $idUser . '.png';

        if (file_exists($rutaFoto)) {
            unlink($rutaFoto);
        }

        echo json_encode([
            'success' => true,
            'mensaje' => 'Alumno y foto eliminados correctamente'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar alumno']);
    }
}
