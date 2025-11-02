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

    /**
     * Intenta hacer login con usuario y contraseña
     * @param string $username Nombre de usuario
     * @param string $password Contraseña en texto plano
     * @return bool true si login exitoso, false si falla
     */
    public static function login($username, $password)
    {
        // Buscar usuario en la BD
        $user = RepoUser::findByUsername($username);
        
        // Si no existe el usuario, retornar false
        if ($user == null) {
            return false;
        }
        
        // Verificar la contraseña
        if (!password_verify($password, $user->getPassword())) {
            return false;
        }
        
        // Obtener el rol del usuario
        $userWithRole = RepoUser::findByIdWithRole($user->getId());
        
        // Guardar en sesión
        Session::abrirsesion();
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getNombreUsuario(),
            'rol_id' => $user->getIdRolFk(),
            'rol_nombre' => $userWithRole['rol_nombre']  // 'admin', 'empresa', 'alumno'
        ];
        
        return true;
    }

    /**
     * Obtiene el rol del usuario actual
     * @return string|null 'admin', 'empresa', 'alumno' o null si no está logeado
     */
    public static function getRol()
    {
        if (!self::estaLogeado()) {
            return null;
        }
        return $_SESSION['user']['rol_nombre'];
    }

    /**
     * Obtiene el ID del usuario actual
     * @return int|null
     */
    public static function getUserId()
    {
        if (!self::estaLogeado()) {
            return null;
        }
        return $_SESSION['user']['id'];
    }

    /**
     * Obtiene el username del usuario actual
     * @return string|null
     */
    public static function getUsername()
    {
        if (!self::estaLogeado()) {
            return null;
        }
        return $_SESSION['user']['username'];
    }

    /**
     * Verifica si el usuario actual tiene un rol específico
     * @param string $rol Nombre del rol ('admin', 'empresa', 'alumno')
     * @return bool
     */
    public static function tieneRol($rol)
    {
        return self::getRol() === $rol;
    }

    /**
     * Verifica si el usuario es administrador
     * @return bool
     */
    public static function esAdmin()
    {
        return self::tieneRol('admin');
    }

    /**
     * Verifica si el usuario es empresa
     * @return bool
     */
    public static function esEmpresa()
    {
        return self::tieneRol('empresa');
    }

    /**
     * Verifica si el usuario es alumno
     * @return bool
     */
    public static function esAlumno()
    {
        return self::tieneRol('alumno');
    }
}