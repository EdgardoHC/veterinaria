<?php
class Encargado
{
    private $idencargado;
    private $nombres;
    private $apellidos;
    private $telefonofijo;
    private $telefonomovil;
    private $dni;
    private $direccion;
    private $correoelectronico;
    private $fechanacimiento;
    private $sexo;

    public function getIdEncargado()
    {
        return $this->idencargado;
    }

    public function setIdEncargado($idencargado)
    {
        $this->idencargado = $idencargado;
    }

    public function getNombres()
    {
        return $this->nombres;
    }

    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function getTelefonoFijo()
    {
        return $this->telefonofijo;
    }

    public function setTelefonoFijo($telefonofijo)
    {
        $this->telefonofijo = $telefonofijo;
    }

    public function getTelefonoMovil()
    {
        return $this->telefonomovil;
    }

    public function setTelefonoMovil($telefonomovil)
    {
        $this->telefonomovil = $telefonomovil;
    }

    public function getDni()
    {
        return $this->dni;
    }

    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function getCorreoElectronico()
    {
        return $this->correoelectronico;
    }

    public function setCorreoElectronico($correoelectronico)
    {
        $this->correoelectronico = $correoelectronico;
    }

    public function getFechaNacimiento()
    {
        return $this->fechanacimiento;
    }

    public function setFechaNacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;
    }

    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }
}
