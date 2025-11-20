<?php

namespace API;

header('Content-Type: application/json');

include_once __DIR__ . '/../../Loaders/miAutoLoader.php';

use Helpers\Session;
use Helpers\Login;
use Helpers\Security;
use Repositories\RepoAlumno;
use Repositories\RepoUser;
use Models\Alumno;
use Models\User;
use Repositories\RepoEstudios;

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
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}







function getAlumnos()
{
    
    Security::verificarToken();
    if (isset($_GET['id'])) {
        $alumno = RepoAlumno::findById($_GET['id']);
        if ($alumno) {
            $user = RepoUser::findById($alumno->getIdUserFk());
            $curriculumBase64 = null;
            if ($alumno->getCurriculum() && $alumno->getCurriculum() !== 'CV pendiente') {
                $curriculumBase64 = base64_encode($alumno->getCurriculum());
            }

            echo json_encode([
                'id' => $alumno->getId(),
                'id_user' => $alumno->getIdUserFk(),
                'username' => $user->getNombreUsuario(),
                'dni' => $alumno->getDni(),
                'email' => $alumno->getEmail(),
                'nombre' => $alumno->getNombre(),
                'ape1' => $alumno->getApe1(),
                'ape2' => $alumno->getApe2(),
                'curriculum' => $curriculumBase64,
                'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                'direccion' => $alumno->getDireccion(),
                'foto' => $alumno->getFoto()
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Alumno no encontrado']);
        }
    } else {
        $resultado = [];
        if (isset($_GET['nombre'])) {
            $nombre = $_GET['nombre'];
            $alumnos = RepoAlumno::findAllByBusqueda($nombre);
        } else {
            $alumnos = RepoAlumno::findAllWithoutCurriculum();
        }

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
        
        // Estudios
        $ciclo_id = $_POST['ciclo_id'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_fin = $_POST['fecha_fin'] ?? null;

        // Validación de campos obligatorios
        if (!$username || !$dni || !$email || !$nombre || !$ape1 || !$password || !$ciclo_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos: username, dni, email, nombre, ape1, password y ciclo son obligatorios']);
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
            null,
            $fecha_nacimiento,
            $direccion,
            null
        );

        if (!RepoAlumno::save($user, $alumno)) {
            throw new \Exception('Error al guardar en BD');
        }

        // Procesar curriculum (BLOB)
        if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
            $curriculumContent = file_get_contents($_FILES['curriculum']['tmp_name']);
            $alumno->setCurriculum($curriculumContent);
        }

        // Procesar foto de perfil (archivo en servidor)
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
            $rutaFoto = "foto_" . $alumno->getIdUserFk() . ".png";

            if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], "../.imagenes/alumno/" . $rutaFoto)) {
                $alumno->setFoto($rutaFoto);
            }
        }

        RepoAlumno::update($alumno);

        // Crear estudios
        $estudios = new \Models\Estudios(
            null,
            $alumno->getId(),
            $ciclo_id,
            $fecha_inicio ?: null,
            $fecha_fin ?: null
        );

        if (!RepoEstudios::save($estudios)) {
            error_log("Advertencia: No se pudieron guardar los estudios del alumno " . $alumno->getId());
        }

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'mensaje' => 'Alumno creado correctamente',
            'id' => $alumno->getId()
        ]);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}










function putAlumno()
{
    Security::verificarToken();
    try {
        //Cojo los datos del json
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = $data['id'];

        // Busco al alumno
        $alumno = RepoAlumno::findById($id);
        if (!$alumno) {
            http_response_code(404);
            echo json_encode(['error' => 'Alumno no encontrado']);
            return;
        }

        $idUser = $alumno->getIdUserFk();

        // Actualizo los datos del alumno
        if (isset($data['dni'])) $alumno->setDni($data['dni']);
        if (isset($data['nombre'])) $alumno->setNombre($data['nombre']);
        if (isset($data['ape1'])) $alumno->setApe1($data['ape1']);
        if (isset($data['ape2'])) $alumno->setApe2($data['ape2']);
        if (isset($data['email'])) $alumno->setEmail($data['email']);
        if (isset($data['fecha_nacimiento'])) $alumno->setFechaNacimiento($data['fecha_nacimiento']);
        if (isset($data['direccion'])) $alumno->setDireccion($data['direccion']);

        // Proceso el curriculum (Base64 a BLOB)
        if (isset($data['curriculum']) && !empty($data['curriculum'])) {
            // El formato es: "data:application/pdf;base64,JVBERi0xLjQK..."
            // Separo el contenido Base64 de data:application/pdf;base64
            $parts = explode(',', $data['curriculum']);

            if (count($parts) === 2) {
                $curriculumBase64 = $parts[1];
                $curriculumContent = base64_decode($curriculumBase64);

                if ($curriculumContent !== false) {
                    $alumno->setCurriculum($curriculumContent);
                }
            }
        }

        // Proceso la foto (Base64 a archivo en servidor)
        if (isset($data['foto']) && !empty($data['foto'])) {
            // El formato es: "data:image/png;base64,JVBERi0xLjQK..."
            // Separo el contenido Base64 de data:image/png;base64
            $parts = explode(',', $data['foto']);

            if (count($parts) === 2) {
                $fotoBase64 = $parts[1];
                $fotoContent = base64_decode($fotoBase64);

                if ($fotoContent !== false) {
                    $rutaFoto = "foto_" . $idUser . ".png";

                    // Guardo archivo en servidor
                    if (file_put_contents("../.imagenes/alumno/" . $rutaFoto, $fotoContent) !== false) {
                        // Solo actualizo la base de datos si antes no tenia foto
                        if ($alumno->getFoto() === null) {
                            $alumno->setFoto($rutaFoto);
                        }
                    }
                }
            }
        }

        RepoAlumno::update($alumno);

        // Actualizo el usuario (username / password)
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
            'mensaje' => 'Alumno actualizado correctamente'
        ]);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}














function deleteAlumno()
{
    Security::verificarToken();
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
