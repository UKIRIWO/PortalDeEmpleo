<?php
namespace Helpers;

class Validator
{
    
    // Validar nombre de usuario
    // - Mínimo 3 caracteres
    // - Solo letras, números y guiones bajos
    public static function validarUsername($username)
    {
        if (empty($username)) {
            return "El nombre de usuario es obligatorio";
        }
        
        if (strlen($username) < 3) {
            return "El nombre de usuario debe tener al menos 3 caracteres";
        }
        
        if (!preg_match('/^[a-zA-Z0-9_ñÑáéíóúÁÉÍÓÚüÜ]+$/', $username)) {
            return "El nombre de usuario solo puede contener letras, números y guiones bajos";
        }
        
        return true;
    }


    // Validar contraseña
    // - Mínimo 6 caracteres
    // - Al menos una letra y un número
    public static function validarPassword($password, $password2 = null)
    {
        if (empty($password)) {
            return "La contraseña es obligatoria";
        }
        
        if (strlen($password) < 4) {
            return "La contraseña debe tener al menos 6 caracteres";
        }
        
        if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return "La contraseña debe contener al menos una letra y un número";
        }
        
        // Validar confirmación de contraseña si se proporciona
        if ($password2 !== null && $password !== $password2) {
            return "Las contraseñas no coinciden";
        }
        
        return true;
    }


    // Validar nombre de empresa
    // - Mínimo 2 caracteres
    // - Solo letras, números, espacios y algunos caracteres especiales

    public static function validarNombreEmpresa($nombre)
    {
        if (empty($nombre)) {
            return "El nombre de la empresa es obligatorio";
        }
        
        if (strlen($nombre) < 2) {
            return "El nombre de la empresa debe tener al menos 2 caracteres";
        }
        
        if (!preg_match('/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚüÜ\-\.&]+$/', $nombre)) {
            return "El nombre de la empresa contiene caracteres no válidos";
        }
        
        return true;
    }


    // Validar dirección
    // - Mínimo 5 caracteres

    public static function validarDireccion($direccion)
    {
        if (empty($direccion)) {
            return "La dirección es obligatoria";
        }
        
        if (strlen($direccion) < 5) {
            return "La dirección debe tener al menos 5 caracteres";
        }
        
        return true;
    }


    // Validar persona de contacto
    // - Mínimo 2 caracteres
    // - Solo letras y espacios

    public static function validarPersonaContacto($persona)
    {
        if (empty($persona)) {
            return "La persona de contacto es obligatoria";
        }
        
        if (strlen($persona) < 2) {
            return "La persona de contacto debe tener al menos 2 caracteres";
        }
        
        if (!preg_match('/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚüÜ]+$/', $persona)) {
            return "La persona de contacto solo puede contener letras y espacios";
        }
        
        return true;
    }


    // Validar correo electrónico

    public static function validarEmail($email)
    {
        if (empty($email)) {
            return "El correo electrónico es obligatorio";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El formato del correo electrónico no es válido";
        }
        
        return true;
    }


    // Validar teléfono (formato español)
    // - 9 dígitos
    // - Puede empezar por 6, 7, 8 o 9

    public static function validarTelefono($telefono)
    {
        if (empty($telefono)) {
            return "El teléfono es obligatorio";
        }
        
        if (strlen($telefono) !== 9) {
            return "El teléfono debe tener 9 dígitos";
        }
        
        if (!preg_match('/^[6789]\d{8}$/', $telefono)) {
            return "El formato del teléfono no es válido (debe empezar por 6, 7, 8 o 9)";
        }
        
        return true;
    }


    // Validar archivo de logo

    public static function validarLogo($logo)
    {
        if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
            // Verificar tipo de archivo
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $tipoArchivo = mime_content_type($logo['tmp_name']);
            
            if (!in_array($tipoArchivo, $tiposPermitidos)) {
                return "El archivo debe ser una imagen (JPEG, PNG, GIF o WebP)";
            }
            
            // Verificar tamaño (máximo 2MB)
            if ($logo['size'] > 2 * 1024 * 1024) {
                return "La imagen no puede ser mayor de 2MB";
            }
        }
        
        return true;
    }


    // Validar todos los campos del formulario de empresa

    public static function validarRegistroEmpresa($datos)
    {
        $errores = [];

        // Validar username
        $resultado = self::validarUsername($datos['username'] ?? '');
        if ($resultado !== true) {
            $errores['username'] = $resultado;
        }

        // Validar password
        $resultado = self::validarPassword($datos['password'] ?? '', $datos['password2'] ?? '');
        if ($resultado !== true) {
            $errores['password'] = $resultado;
        }

        // Validar nombre empresa
        $resultado = self::validarNombreEmpresa($datos['nombreEmpresa'] ?? '');
        if ($resultado !== true) {
            $errores['nombreEmpresa'] = $resultado;
        }

        // Validar dirección
        $resultado = self::validarDireccion($datos['direccion'] ?? '');
        if ($resultado !== true) {
            $errores['direccion'] = $resultado;
        }

        // Validar persona de contacto
        $resultado = self::validarPersonaContacto($datos['persona_de_contacto'] ?? '');
        if ($resultado !== true) {
            $errores['persona_de_contacto'] = $resultado;
        }

        // Validar email
        $resultado = self::validarEmail($datos['correo_de_contacto'] ?? '');
        if ($resultado !== true) {
            $errores['correo_de_contacto'] = $resultado;
        }

        // Validar teléfono
        $resultado = self::validarTelefono($datos['telefono_de_contacto'] ?? '');
        if ($resultado !== true) {
            $errores['telefono_de_contacto'] = $resultado;
        }

        // Validar logo (si se subió)
        $resultado = self::validarLogo($_FILES['logo'] ?? null);
        if ($resultado !== true) {
            $errores['logo'] = $resultado;
        }

        return empty($errores) ? true : $errores;
    }
}