<?php
namespace Models;
class Empresa
{
    private $id;
    private $nombre;
    private $idUserFk;
    private $direccion;
    private $personaDeContacto;
    private $correoDeContacto;
    private $telefonoDeContacto;
    private $logo;


    public function __construct($id = null, $nombre = "", $idUserFk = null, $direccion = "", $personaDeContacto = "", $correoDeContacto = "", $telefonoDeContacto = "", $logo = "")
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->idUserFk = $idUserFk;
        $this->direccion = $direccion;
        $this->personaDeContacto = $personaDeContacto;
        $this->correoDeContacto = $correoDeContacto;
        $this->telefonoDeContacto = $telefonoDeContacto;
        $this->logo = $logo;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getIdUserFk() { return $this->idUserFk; }
    public function setIdUserFk($idUserFk) { $this->idUserFk = $idUserFk; }

    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }

    public function getPersonaDeContacto() { return $this->personaDeContacto; }
    public function setPersonaDeContacto($personaDeContacto) { $this->personaDeContacto = $personaDeContacto; }

    public function getCorreoDeContacto() { return $this->correoDeContacto; }
    public function setCorreoDeContacto($correoDeContacto) { $this->correoDeContacto = $correoDeContacto; }

    public function getTelefonoDeContacto() { return $this->telefonoDeContacto; }
    public function setTelefonoDeContacto($telefonoDeContacto) { $this->telefonoDeContacto = $telefonoDeContacto; }

    public function getLogo() { return $this->logo; }
    public function setLogo($logo) { $this->logo = $logo; }
}
