<?php
class Padecimiento
{
    private $idPadecimiento;
    private $nombre;

    public function getIdPadecimiento()
    {
        return $this->idPadecimiento;
    }

    public function setIdPadecimiento($idPadecimiento)
    {
        $this->idPadecimiento = $idPadecimiento;
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
