<?php
namespace Models;
class Oferta
{
    private $id;
    private $idEmpresaFk;
    private $fechaInicio;
    private $fechaFin;
    private $titulo;
    private $descripcion;

    public function __construct($id = null, $idEmpresaFk = null, $fechaInicio = "", $fechaFin = "", $titulo = "", $descripcion = "")
    {
        $this->id = $id;
        $this->idEmpresaFk = $idEmpresaFk;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdEmpresaFk() { return $this->idEmpresaFk; }
    public function setIdEmpresaFk($idEmpresaFk) { $this->idEmpresaFk = $idEmpresaFk; }

    public function getFechaInicio() { return $this->fechaInicio; }
    public function setFechaInicio($fechaInicio) { $this->fechaInicio = $fechaInicio; }

    public function getFechaFin() { return $this->fechaFin; }
    public function setFechaFin($fechaFin) { $this->fechaFin = $fechaFin; }

    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
