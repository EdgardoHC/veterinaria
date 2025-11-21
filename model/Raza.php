<?php
class Raza
{
    private $idRaza;
    private $nombre;

    public function getIdRaza()
    {
        return $this->idRaza;
    }

    public function setIdRaza($idRaza)
    {
        $this->idRaza = $idRaza;
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
