<?php
namespace Models;
class Ciclo
{
    private $id;
    private $nombre;
    private $nivel;
    private $familiaFk;

    public function __construct($id = null, $nombre="",  $nivel = "", $familiaFk = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->familiaFk = $familiaFk;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getNivel() { return $this->nivel; }
    public function setNivel($nivel) { $this->nivel = $nivel; }

    public function getFamiliaFk() { return $this->familiaFk; }
    public function setFamiliaFk($familiaFk) { $this->familiaFk = $familiaFk; }
}
