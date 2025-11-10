<?php
namespace Models;
class OfertaCiclo
{
    private $id;
    private $idCicloFk;
    private $idOfertaFk;
    private $required;

    public function __construct($id = null, $idCicloFk = null, $idOfertaFk = null)
    {
        $this->id = $id;
        $this->idCicloFk = $idCicloFk;
        $this->idOfertaFk = $idOfertaFk;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdCicloFk() { return $this->idCicloFk; }
    public function setIdCicloFk($idCicloFk) { $this->idCicloFk = $idCicloFk; }

    public function getIdOfertaFk() { return $this->idOfertaFk; }
    public function setIdOfertaFk($idOfertaFk) { $this->idOfertaFk = $idOfertaFk; }
}
