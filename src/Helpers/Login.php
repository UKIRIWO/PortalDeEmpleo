<?php
namespace Helpers;
include_once "Session.php"; 
use Repositories\RepoRol;
use Repositories\RepoUser;
class Login
{
    public static function estaLogeado()
    {
        Session::abrirsesion();
        return isset($_SESSION['user']);
    }

    public static function logout()
    {
        $idUser = $_SESSION['user']['id'];

        if ($idUser) {
            Security::eliminarToken($idUser);
        }

        //borra la caché para que no se pueda volver a páginas anteriores sin tener que volve r a pasar por la validación
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");
        unset($_SESSION['user']);
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        Session::cerrarsesion();
    }

    public static function login($username, $password)
    {
        //busca al usuario, si lo encuentra se verifica su contraseña
        $user = RepoUser::findByUsername($username);

        if ($user == null) {
            return false;
        }
        
        if (!password_verify($password, $user->getPassword())) {
            return false;
        }
        
        $rol = RepoRol::findRolByUser($user->getId());
        
        Session::abrirsesion();

        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getNombreUsuario(),
            'rol_id' => $user->getIdRolFk(),
            'rol_nombre' => $rol->getNombre()  // 'admin', 'empresa', 'alumno'
        ];
        
        return true;
    }

    public static function getRol()
    {
        if (!self::estaLogeado()) {
            return null;
        }
        return $_SESSION['user']['rol_nombre'];
    }


    public static function getUserId()
    {
        if (!self::estaLogeado()) {
            error_log("NO ESTA LOGEADO");
            return null;
        }
        return $_SESSION['user']['id'];
    }

    public static function getUsername()
    {
        if (!self::estaLogeado()) {
            return null;
        }
        return $_SESSION['user']['username'];
    }


    public static function tieneRol($rol)
    {
        return self::getRol() === $rol;
    }

    public static function esAdmin()
    {
        return self::tieneRol('admin');
    }


    public static function esEmpresa()
    {
        return self::tieneRol('empresa');
    }

    public static function esAlumno()
    {
        return self::tieneRol('alumno');
    }
}