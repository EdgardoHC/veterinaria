<?php
class Usuario
{
    private $idUsuario;
    private $nombre;
    private $apellidos;
    private $email;
    private $apodo;
    private $pwd;

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
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getApodo()
    {
        return $this->apodo;
    }
    public function setApodo($apodo)
    {
        $this->apodo = $apodo;
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
