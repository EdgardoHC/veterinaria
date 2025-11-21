<?php
class Vacuna
{
    private $idvacuna;
    private $nombre;

    public function getIdVacuna()
    {
        return $this->idvacuna;
    }

    public function setIdVacuna($idvacuna)
    {
        $this->idvacuna = $idvacuna;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
}
