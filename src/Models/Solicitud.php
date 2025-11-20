<?php
namespace Models;
class Solicitud
{
    private $id;
    private $idOfertaFk;
    private $idAlumnoFk;
    private $fechaSolicitud;
    private $estado;
    private $favorito;

    public function __construct($id = null, $idOfertaFk = null, $idAlumnoFk = null, $fechaSolicitud = "", $estado = "pendiente", $favorito = 0)
    {
        $this->id = $id;
        $this->idOfertaFk = $idOfertaFk;
        $this->idAlumnoFk = $idAlumnoFk;
        $this->fechaSolicitud = $fechaSolicitud;
        $this->estado = $estado;
        $this->favorito = $favorito;
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdOfertaFk() { return $this->idOfertaFk; }
    public function setIdOfertaFk($idOfertaFk) { $this->idOfertaFk = $idOfertaFk; }

    public function getIdAlumnoFk() { return $this->idAlumnoFk; }
    public function setIdAlumnoFk($idAlumnoFk) { $this->idAlumnoFk = $idAlumnoFk; }

    public function getFechaSolicitud() { return $this->fechaSolicitud; }
    public function setFechaSolicitud($fechaSolicitud) { $this->fechaSolicitud = $fechaSolicitud; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getFavorito() { return $this->favorito; }
    public function setFavorito($favorito) { $this->favorito = $favorito; }
}
