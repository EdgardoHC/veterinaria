<?php
class Usuario
{
    private $idUsuario;
    private $nombrecompleto;
    private $nombreusuario;
    private $email;
    private $idrol;
    private $pwd;
    private $estado;

    // Getters y Setters
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getNombre()
    {
        return $this->nombrecompleto;
    }
    public function setNombre($nombre)
    {
        $this->nombrecompleto = $nombre;
    }

    public function getNombreUsuario()
    {
        return $this->nombreusuario;
    }
    public function setNombreUsuario($username)
    {
        $this->nombreusuario = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getIdRol()
    {
        return $this->idrol;
    }
    public function setIdRol($rol)
    {
        $this->idrol = $rol;
    }

    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($est)
    {
        $this->estado = $est;
    }

    public function getPwd()
    {
        return $this->pwd;
    }
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }
}
