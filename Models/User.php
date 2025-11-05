<?php
namespace Models;
class User
{
    private $id;
    private $nombreUsuario;
    private $password;
    private $idRolFk;

    public function __construct($id = null, $nombreUsuario = "", $password = "", $idRolFk = null)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->idRolFk = $idRolFk;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNombreUsuario() { return $this->nombreUsuario; }
    public function setNombreUsuario($nombreUsuario) { $this->nombreUsuario = $nombreUsuario; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getIdRolFk() { return $this->idRolFk; }
    public function setIdRolFk($idRolFk) { $this->idRolFk = $idRolFk; }
}
