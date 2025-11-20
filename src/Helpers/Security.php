<?php

namespace Helpers;

include_once __DIR__ . '/../rutaLoader.php';

use Repositories\RepoToken;

class Security
{

    public static function generarToken()
    {
        return bin2hex(random_bytes(16)); // cadena aleatoria de 16 bytes o 32 hexadecimales
    }


    public static function guardarTokenEnDB($idUser, $token)
    {
        try {
            // Verifico si ya existe un token para este usuario
            $tokenExistente = RepoToken::findByUserId($idUser);

            if ($tokenExistente) {
                // Actualizo el token existente
                return RepoToken::update($idUser, $token);
            } else {
                // Si no existe creo un  nuevo token
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


        //coge el token de la cabecera
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        //busca si el token pertenece a un usuario
        $usuario = RepoToken::findUserByToken($token);

        if (!$usuario) {
            http_response_code(401);
            exit;
        }

        return $usuario;
    }
}
