<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';

use Repositories\RepoUser;
use Repositories\RepoEmpresa;
use Models\User;
use Models\Empresa;
use Helpers\Validator;

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
            'titulo' => 'RegistroEmpresa',
            'errores' => $_SESSION['errores'] ?? [],
            'datos_anteriores' => $_SESSION['datos_anteriores'] ?? []
        ];

        // Limpiar errores de la sesión después de mostrarlos
        unset($_SESSION['errores']);
        unset($_SESSION['datos_anteriores']);

        echo $this->templates->render('pages/RegistroEmpresa', $data);
    }

    public function procesarRegistro()
    {
        // Validar todos los campos
        $validacion = Validator::validarRegistroEmpresa($_POST);
        
        if ($validacion !== true) {
            $_SESSION['errores'] = $validacion;
            $_SESSION['datos_anteriores'] = $_POST;
            header("Location: index.php?menu=RegistroEmpresa");
            exit;
        }


        // Obtengo datos del formulario

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $nombre = $_POST['nombreEmpresa'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $personaContacto = $_POST['persona_de_contacto'] ?? null;
        $correoContacto = $_POST['correo_de_contacto'] ?? null;
        $telefonoContacto = $_POST['telefono_de_contacto'] ?? null;

        // Verifico si el username ya existe
        $userExistente = RepoUser::findByUsername($username);
        if ($userExistente) {
            $_SESSION['error'] = 'El nombre de usuario ya está en uso';
            header("Location: index.php?menu=RegistroEmpresa");
            exit;
        }

        //si existe la foto la guardo
        $logo = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logo = $_FILES['logo'];
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
            null
        );

        return [
            'user' => $user,
            'empresa' => $empresa,
            'logo' => $logo
        ];
    }

    public function procesarRegistroCandidata()
    {

        // si se hace el registro sin usuario se cogerán los datos al igual que una empresa normal
        // y se guardarán en la tabla de empresas candidatas
        $registro = self::procesarRegistro();

        $user = $registro['user'];
        $empresa = $registro['empresa'];
        $logo = $registro['logo'];

        RepoEmpresa::saveCandidata($user, $empresa);

        self::procesarLogo($empresa, $logo);

        header("Location: Index.php?menu=Login");
    }


    public function procesarRegistroEmpresa()
    {
        // si se hace el registro desde el panel de admin se cogerán los datos
        // y se guardarán en la tabla de empresas ya dadas de alta
        $registro = self::procesarRegistro();

        $user = $registro['user'];
        $empresa = $registro['empresa'];
        $logo = $registro['logo'];

        RepoEmpresa::saveWithUser($user, $empresa);

        self::procesarLogo($empresa, $logo);

        header("Location: Index.php?menu=PanelAdmin");
    }

    public function procesarLogo($empresa, $logo)
    {
        // si el logo no es null lo guardaré en root/.imagenes/empresa/logo_(IdUser).png y en la base de datos guardaré el nombre del archivo
        if ($logo) {
            $nombreArchivo = 'logo_' . $empresa->getIdUserFk() . '.png';
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/.imagenes/empresa/' . $nombreArchivo)) {
                $empresa->setLogo($nombreArchivo);
                RepoEmpresa::update($empresa);
            }
        }
    }
}
