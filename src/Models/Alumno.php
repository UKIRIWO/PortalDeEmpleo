<?php
namespace Models;
class Alumno
{
    private $id;
    private $idUserFk;
    private $dni;
    private $email;
    private $nombre;
    private $ape1;
    private $ape2;
    private $curriculum;
    private $fechaNacimiento;
    private $direccion;
    private $foto;

    public function __construct($id = null, $idUserFk = null, $dni = "", $email = "", $nombre = "", $ape1 = "", $ape2 = "", $curriculum = null, $fechaNacimiento = "", $direccion = "", $foto = "")
    {
        $this->id = $id;
        $this->idUserFk = $idUserFk;
        $this->dni = $dni;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->ape1 = $ape1;
        $this->ape2 = $ape2;
        $this->curriculum = $curriculum;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->direccion = $direccion;
        $this->foto = $foto;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdUserFk() { return $this->idUserFk; }
    public function setIdUserFk($idUserFk) { $this->idUserFk = $idUserFk; }

    public function getDni() { return $this->dni; }
    public function setDni($dni) { $this->dni = $dni; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getApe1() { return $this->ape1; }
    public function setApe1($ape1) { $this->ape1 = $ape1; }

    public function getApe2() { return $this->ape2; }
    public function setApe2($ape2) { $this->ape2 = $ape2; }

    public function getCurriculum() { return $this->curriculum; }
    public function setCurriculum($curriculum) { $this->curriculum = $curriculum; }

    public function getFechaNacimiento() { return $this->fechaNacimiento; }
    public function setFechaNacimiento($fechaNacimiento) { $this->fechaNacimiento = $fechaNacimiento; }

    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }

    public function getFoto() { return $this->foto; }
    public function setFoto($foto) { $this->foto = $foto; }
}
