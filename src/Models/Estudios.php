<?php
namespace Models;
class Estudios
{
    private $id;
    private $idAlumnoFk;
    private $idCicloFk;
    private $fechaInicio;
    private $fechaFin;

    public function __construct($id = null, $idAlumnoFk = null, $idCicloFk = null, $fechaInicio = "", $fechaFin = "")
    {
        $this->id = $id;
        $this->idAlumnoFk = $idAlumnoFk;
        $this->idCicloFk = $idCicloFk;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdAlumnoFk() { return $this->idAlumnoFk; }
    public function setIdAlumnoFk($idAlumnoFk) { $this->idAlumnoFk = $idAlumnoFk; }

    public function getIdCicloFk() { return $this->idCicloFk; }
    public function setIdCicloFk($idCicloFk) { $this->idCicloFk = $idCicloFk; }

    public function getFechaInicio() { return $this->fechaInicio; }
    public function setFechaInicio($fechaInicio) { $this->fechaInicio = $fechaInicio; }

    public function getFechaFin() { return $this->fechaFin; }
    public function setFechaFin($fechaFin) { $this->fechaFin = $fechaFin; }
}
