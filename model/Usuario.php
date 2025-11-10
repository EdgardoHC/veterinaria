<?php

class Usuario
{
    private $idUsuario;
    private $nombre;
    private $email;
    private $nombreUsuario;
    private $pwd;
    private $estado;
    private $idRol;
    private $idEmpleado;

    // ID
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = (int)$idUsuario;
    }

    // NOMBRE
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

   
    //EMAIL
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    //NOMBRE DE USUARIO / APODO 
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    //PASSWORD 
    public function getPwd()
    {
        return $this->pwd;
    }

    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }

    // ESTADO (1 activo, 0 inactivo) 
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = (int)$estado;
    }

    //ROL 
    public function getIdRol()
    {
        return $this->idRol;
    }

    public function setIdRol($idRol)
    {
        $this->idRol = (int)$idRol;
    }
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    public function setIdEmpleado($id)
    {
        $this->idEmpleado = (int)$id;
    }
}
