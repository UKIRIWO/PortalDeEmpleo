<?php
include_once "Session.php";

class Login
{
    public static function estaLogeado()
    {
        Session::abrirsesion();
        return isset($_SESSION['user']);
    }

    public static function logout()
    {
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
        // Busca un usuario en la BD
        $user = RepoUser::findByUsername($username);

        if ($user == null) {
            return false;
        }
        
        // Verifica la contraseña
        if (!password_verify($password, $user->getPassword())) {
            return false;
        }
        
        // Cojo el rol del usuario
        $userWithRole = RepoUser::findByIdWithRole($user->getId());
        
        // Guarda la sesión
        Session::abrirsesion();
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getNombreUsuario(),
            'rol_id' => $user->getIdRolFk(),
            'rol_nombre' => $userWithRole['rol_nombre']  // 'admin', 'empresa', 'alumno'
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