<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../Loaders/miAutoLoader.php';

use Repositories\RepoUser;
use Helpers\Security;

$response = [];
$statusCode = 200;

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? null;
    $password = $input['password'] ?? null;

    if (!$username || !$password) {
        $statusCode = 400;
        $response = ['error' => 'Faltan datos'];
    } else {
        $usuario = RepoUser::verificarUsuario($username, $password);
        error_log("Resultado verificarUsuario: " . print_r($usuario, true));

        if ($usuario) {
            $token = Security::generarToken();
            Security::guardarTokenEnDB($usuario['id'], $token);
            $response = ['token' => $token];
        } else {
            error_log("Usuario o contraseña incorrectos para: $username");
            $response = ['token' => ''];
        }
    }
} catch (Exception $e) {
    $statusCode = 500;
    $response = ['error' => 'Error interno', 'detalle' => $e->getMessage()];
}


http_response_code($statusCode);
echo json_encode($response);