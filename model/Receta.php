<?php
class Receta {
    private $idreceta;
    private $fecha;
    private $descripcion;
    private $idexpedientedetalle;
    private $dosis;
    
    public function getIdReceta() { return $this->idreceta; }
    public function setIdReceta($idreceta) { $this->idreceta = $idreceta; }
    
    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    
    public function getIdExpedienteDetalle() { return $this->idexpedientedetalle; }
    public function setIdExpedienteDetalle($idexpedientedetalle) { $this->idexpedientedetalle = $idexpedientedetalle; }
    
    public function getDosis() { return $this->dosis; }
    public function setDosis($dosis) { $this->dosis = $dosis; }
}
?>