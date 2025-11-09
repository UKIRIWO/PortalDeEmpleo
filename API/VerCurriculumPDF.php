<?php
namespace API;
use Helpers\Login;
use Helpers\Session;
use Repositories\RepoAlumno;

include_once '../Loaders/miAutoLoader.php';

Session::abrirsesion();

// Verificar que esté logeado y sea admin
if (!Login::estaLogeado() || !Login::esAdmin()) {
    http_response_code(403);
    die('No autorizado');
}

// Obtener ID del alumno
$idAlumno = $_GET['id'] ?? null;

if (!$idAlumno) {
    http_response_code(400);
    die('ID de alumno no proporcionado');
}

// Obtener alumno de la BD
$alumno = RepoAlumno::findById($idAlumno);

if (!$alumno) {
    http_response_code(404);
    die('Alumno no encontrado');
}

// Obtener el curriculum (BLOB)
$curriculumBlob = $alumno->getCurriculum();

if (!$curriculumBlob || $curriculumBlob === 'CV pendiente') {
    http_response_code(404);
    die('Este alumno no tiene curriculum cargado');
}

// Enviar el PDF directamente al navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="curriculum_alumno_' . $idAlumno . '.pdf"');
header('Content-Length: ' . strlen($curriculumBlob));
echo $curriculumBlob;
exit;