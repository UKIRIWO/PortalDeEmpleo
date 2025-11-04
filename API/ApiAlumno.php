<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        //getAlumnos(); //get all
        break;
    case 'POST': 
        //postAlumno(); // crear alumno
        break;
    case 'PUT': 
        putAlumno(); //update
        break;
    case 'DELETE': 
        //deleteAlumno();  //delete 
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}


function putAlumno() {
    header('Content-Type: application/json'); // asegurarnos de que siempre sea JSON
    try {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

       

        $idAlumno = $data['id'] ?? null;
        $nombre   = $data['nombre'] ?? null;
        $email    = $data['email'] ?? null;
         //validar los datos completos
        if (!$idAlumno || !$nombre || !$email) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos obligatorios"]);
          
        } else {

        $alumno = RepoAlumno::findById($idAlumno);
        if (!$alumno) {
            http_response_code(404);
            echo json_encode(["error" => "Alumno no encontrado"]);
            
        } else {

        $user = RepoUser::findById($alumno->getIdUserFK());
        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario del alumno no encontrado"]);
            
        } else{

        $user->setNombreUsuario($email);
        RepoUser::update($user);

        $alumno->setNombre($nombre);
        RepoAlumno::update($alumno);

        echo json_encode(["mensaje" => "Alumno actualizado correctamente"]);
        }
    }
}
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Ocurrió un error al actualizar el alumno",
            "detalle" => $e->getMessage()
        ]);
    }
}

