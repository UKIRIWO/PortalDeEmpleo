<?php

namespace Controllers;

include_once __DIR__ . '/../rutaLoader.php';


use Repositories\RepoEmpresa;
use Repositories\RepoUser;

class EditarEmpresaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = Engine::getEngine();
    }

    public function index()
    {
        $empresa = RepoEmpresa::findById($_GET['id']);
        $user = RepoUser::findById($empresa->getIdUserFk());
        $data = [
            'titulo' => 'Editar Empresa',
            'empresa' => $empresa,
            'user' => $user
        ];

        echo $this->templates->render('pages/EditarEmpresa', $data);
    }

    public function procesarCambios()
    {
        try {
            $idEmpresa = $_POST['id_empresa'] ?? null;
            if (!$idEmpresa) {
                throw new \Exception("No se recibió el id de empresa");
            }

            $empresa = RepoEmpresa::findById($idEmpresa);
            if (!$empresa) throw new \Exception("Empresa no encontrada");

            $user = RepoUser::findById($empresa->getIdUserFk());
            if (!$user) throw new \Exception("Usuario no encontrado");

            // Datos del formulario
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $personaContacto = $_POST['persona_de_contacto'] ?? null;
            $correoContacto = $_POST['correo_de_contacto'] ?? null;
            $telefonoContacto = $_POST['telefono_de_contacto'] ?? null;
            $logo = $_FILES['logo'] ?? null;

            if (!$username || !$nombre) {
                throw new \Exception("Faltan campos obligatorios");
            }

            
            $user->setNombreUsuario($username);
            if (!empty($password)) {
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            }

            $empresa->setNombre($nombre);
            $empresa->setDireccion($direccion);
            $empresa->setPersonaDeContacto($personaContacto);
            $empresa->setCorreoDeContacto($correoContacto);
            $empresa->setTelefonoDeContacto($telefonoContacto);

            if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = 'logo_' . $empresa->getIdUserFk() . '.png';
                $rutaDestino = __DIR__ . '/../Public/.imagenes/empresa/' . $nombreArchivo;

                if (move_uploaded_file($logo['tmp_name'], $rutaDestino)) {
                    $empresa->setLogo($nombreArchivo);
                }
            }

            RepoUser::update($user);
            RepoEmpresa::update($empresa);

            header("Location: index.php?menu=PanelAdmin");
            exit;
        } catch (\Exception $e) {
            error_log("Error en EditarEmpresaController::procesarCambios(): " . $e->getMessage());
            $_SESSION['error'] = "Error al actualizar la empresa.";
            header("Location: index.php?menu=PanelAdmin");
            exit;
        }
    }
}
