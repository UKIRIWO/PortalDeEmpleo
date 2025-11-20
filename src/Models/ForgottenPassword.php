<?php
namespace Models;
class ForgottenPassword
{
    private $id;
    private $idUserFk;
    private $token;
    private $oldPassword;
    private $fechaCreacion;
    private $fechaExpiracion;
    private $used;

    public function __construct($id = null, $idUserFk = null, $token = "", $oldPassword = "", $fechaCreacion = "", $fechaExpiracion = "", $used = false)
    {
        $this->id = $id;
        $this->idUserFk = $idUserFk;
        $this->token = $token;
        $this->oldPassword = $oldPassword;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaExpiracion = $fechaExpiracion;
        $this->used = $used;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdUserFk() { return $this->idUserFk; }
    public function setIdUserFk($idUserFk) { $this->idUserFk = $idUserFk; }

    public function getToken() { return $this->token; }
    public function setToken($token) { $this->token = $token; }

    public function getOldPassword() { return $this->oldPassword; }
    public function setOldPassword($oldPassword) { $this->oldPassword = $oldPassword; }

    public function getFechaCreacion() { return $this->fechaCreacion; }
    public function setFechaCreacion($fechaCreacion) { $this->fechaCreacion = $fechaCreacion; }

    public function getFechaExpiracion() { return $this->fechaExpiracion; }
    public function setFechaExpiracion($fechaExpiracion) { $this->fechaExpiracion = $fechaExpiracion; }

    public function getUsed() { return $this->used; }
    public function setUsed($used) { $this->used = $used; }
}
