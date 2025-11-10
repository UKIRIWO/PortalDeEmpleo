<?php

namespace Controllers;

include_once "../Loaders/miAutoLoader.php";

use Repositories\RepoUser;
use Repositories\RepoEmpresa;
use Models\User;
use Models\Empresa;

class RegistroEmpresaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        $data = [
            'titulo' => 'RegistroEmpresa'
        ];

        echo $this->templates->render('pages/RegistroEmpresa', $data);
    }

    public function procesarRegistro()
    {
        // Obtener datos del formulario
        error_log("procesando formulario");
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $nombre = $_POST['nombreEmpresa'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $personaContacto = $_POST['persona_de_contacto'] ?? null;
        $correoContacto = $_POST['correo_de_contacto'] ?? null;
        $telefonoContacto = $_POST['telefono_de_contacto'] ?? null;

        // Log de depuración: valores recibidos del formulario
        error_log("=== [Registro Empresa] Datos recibidos del formulario ===");
        error_log("Username: " . ($username ?? 'NULL'));
        error_log("Password: " . ($password ?? 'NULL'));
        error_log("Nombre empresa: " . ($nombre ?? 'NULL'));
        error_log("Dirección: " . ($direccion ?? 'NULL'));
        error_log("Persona de contacto: " . ($personaContacto ?? 'NULL'));
        error_log("Correo de contacto: " . ($correoContacto ?? 'NULL'));
        error_log("Teléfono de contacto: " . ($telefonoContacto ?? 'NULL'));
        error_log("===========================================================");

        // Validar campos obligatorios
        if (!$username || !$password || !$nombre || !$direccion || !$personaContacto || !$correoContacto || !$telefonoContacto) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header("Location: index.php?menu=RegistroEmpresa");
            exit;
        }

        // Verificar si el username ya existe
        $userExistente = RepoUser::findByUsername($username);
        if ($userExistente) {
            $_SESSION['error'] = 'El nombre de usuario ya está en uso';
            header("Location: index.php?menu=RegistroEmpresa");
            exit;
        }

        // Procesar logo (archivo)
        $logo = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'logo_' . time() . '.' . $extension;



            if (move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/../.imagenes/empresa/' . $nombreArchivo)) {
                $logo = $nombreArchivo;
            }
        }

        $user = new User(
            null,
            $username,
            password_hash($password, PASSWORD_DEFAULT),
            2
        );

        $empresa = new Empresa(
            null,
            null,
            $nombre,
            $direccion,
            $personaContacto,
            $correoContacto,
            $telefonoContacto,
            $logo
        );

        // ==== LOG DE USUARIO ====
        error_log("=== DATOS USER ===");
        error_log("Username: " . $user->getNombreUsuario());
        error_log("Password (hash): " . $user->getPassword());
        error_log("Rol ID: " . $user->getIdRolFk());

        // ==== LOG DE EMPRESA ====
        error_log("=== DATOS EMPRESA ===");
        error_log("Nombre: " . $empresa->getNombre());
        error_log("Dirección: " . $empresa->getDireccion());
        error_log("Persona de contacto: " . $empresa->getPersonaDeContacto());
        error_log("Correo de contacto: " . $empresa->getCorreoDeContacto());
        error_log("Teléfono de contacto: " . $empresa->getTelefonoDeContacto());
        error_log("Logo: " . $empresa->getLogo());
        error_log("Devolviendo user y empresa");
        return [
            'user' => $user,
            'empresa' => $empresa
        ];
    }

    public function procesarRegistroCandidata()
    {
        $registro = self::procesarRegistro();

        $user = $registro['user'];
        $empresa = $registro['empresa'];

        RepoEmpresa::saveCandidata($user, $empresa);

        header("Location: Index.php?menu=Login");
    }

    public function procesarRegistroEmpresa()
    {
        $registro = self::procesarRegistro();

        $user = $registro['user'];
        $empresa = $registro['empresa'];
        error_log("Guardando empresa en la BD");
        RepoEmpresa::saveWithUser($user, $empresa);
        error_log("Empresa Guardada con éxito");
        header("Location: Index.php?menu=PanelAdmin");
    }

    public function procesarPanelAdmin() {}
}
