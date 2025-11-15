<?php

namespace Helpers;

include_once "../Loaders/miAutoLoader.php";

use Repositories\RepoToken;

class Security
{

    public static function generarToken()
    {
        return bin2hex(random_bytes(16)); // 16 bytes = 32 caracteres hexadecimales
    }


    public static function guardarTokenEnDB($idUser, $token)
    {
        try {
            // Verificar si ya existe un token para este usuario
            $tokenExistente = RepoToken::findByUserId($idUser);

            if ($tokenExistente) {
                // Actualizar token existente
                return RepoToken::update($idUser, $token);
            } else {
                // Crear nuevo token
                return RepoToken::save($idUser, $token);
            }
        } catch (\Exception $e) {
            error_log("Error al guardar token: " . $e->getMessage());
            return false;
        }
    }

    public static function eliminarToken($idUser)
    {
        try {
            return RepoToken::delete($idUser);
        } catch (\Exception $e) {
            error_log("Error al eliminar token: " . $e->getMessage());
            return false;
        }
    }

    public static function verificarToken()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $token = null;


        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        // error_log("token: ". $token);

        $usuario = RepoToken::findUserByToken($token);

        // error_log("usuario:" . $usuario['nombre_Usuario']);
        // error_log("Match token: " . preg_match('/Bearer\s(\S+)/', $authHeader, $matches));
        if (!$usuario) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido o expirado']);
            exit;
        }

        return $usuario;
    }
}
