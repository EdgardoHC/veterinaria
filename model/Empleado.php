<?php

class Empleado
{
    private $idEmpleado;
    private $nombres;
    private $apellidos; 

    public function getIdEmpleado(

    ) { return $this->idEmpleado; }

    public function setIdEmpleado($id)
     { $this->idEmpleado = (int)$id; }

    public function getNombres()
     { return $this->nombres; }

    public function setNombres($nombres)
     { $this->nombres = $nombres; }

    public function getApellidos()
     { return $this->apellidos; }

    public function setApellidos($apellidos)
     { $this->apellidos = $apellidos; }

    public function getNombreCompleto()
    {
        return trim(preg_replace('/\s+/', ' ', trim($this->nombres . ' ' . $this->apellidos)));
    }
}
