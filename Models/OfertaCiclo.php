<?php

class OfertaCiclo
{
    private $id;
    private $idCicloFk;
    private $idOfertaFk;
    private $required;

    public function __construct($id = null, $idCicloFk = null, $idOfertaFk = null, $required = false)
    {
        $this->id = $id;
        $this->idCicloFk = $idCicloFk;
        $this->idOfertaFk = $idOfertaFk;
        $this->required = $required;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdCicloFk() { return $this->idCicloFk; }
    public function setIdCicloFk($idCicloFk) { $this->idCicloFk = $idCicloFk; }

    public function getIdOfertaFk() { return $this->idOfertaFk; }
    public function setIdOfertaFk($idOfertaFk) { $this->idOfertaFk = $idOfertaFk; }

    public function getRequired() { return $this->required; }
    public function setRequired($required) { $this->required = $required; }
}
