<?php
class Expediente {
    private $idexpediente;
    private $fecha;
    private $idmascota;
    private $descripcion;
    private $idusuario;
    
    public function getIdExpediente() { return $this->idexpediente; }
    public function setIdExpediente($idexpediente) { $this->idexpediente = $idexpediente; }
    
    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    
    public function getIdMascota() { return $this->idmascota; }
    public function setIdMascota($idmascota) { $this->idmascota = $idmascota; }
    
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    
    public function getIdUsuario() { return $this->idusuario; }
    public function setIdUsuario($idusuario) { $this->idusuario = $idusuario; }
}

class ExpedienteDetalle {
    private $idexpedientedetalle;
    private $fecha;
    private $resumen;
    private $diagnostico;
    private $idexpediente;
    private $peso;
    private $altura;
    private $idusuario;
    
    public function getIdExpedienteDetalle() { return $this->idexpedientedetalle; }
    public function setIdExpedienteDetalle($idexpedientedetalle) { $this->idexpedientedetalle = $idexpedientedetalle; }
    
    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    
    public function getResumen() { return $this->resumen; }
    public function setResumen($resumen) { $this->resumen = $resumen; }
    
    public function getDiagnostico() { return $this->diagnostico; }
    public function setDiagnostico($diagnostico) { $this->diagnostico = $diagnostico; }
    
    public function getIdExpediente() { return $this->idexpediente; }
    public function setIdExpediente($idexpediente) { $this->idexpediente = $idexpediente; }
    
    public function getPeso() { return $this->peso; }
    public function setPeso($peso) { $this->peso = $peso; }
    
    public function getAltura() { return $this->altura; }
    public function setAltura($altura) { $this->altura = $altura; }
    
    public function getIdUsuario() { return $this->idusuario; }
    public function setIdUsuario($idusuario) { $this->idusuario = $idusuario; }
}
?>