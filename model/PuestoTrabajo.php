<?php
/**
 * Entidad: Puesto de Trabajo
 * Representa la tabla 'puestodetrabajo'
 */
class PuestoTrabajo implements JsonSerializable
{
    private $idPuestoTrabajo;
    private $nombre;
    private $idAreaTrabajo; // La llave foránea
    
    // Propiedad auxiliar para mostrar el nombre del área en el listado
    private $nombreArea; 

    // --- Getters ---
    public function getIdPuestoTrabajo() {
        return $this->idPuestoTrabajo;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getIdAreaTrabajo() {
        return $this->idAreaTrabajo;
    }
    public function getNombreArea() {
        return $this->nombreArea;
    }

    // --- Setters ---
    public function setIdPuestoTrabajo($idPuestoTrabajo) {
        $this->idPuestoTrabajo = $idPuestoTrabajo;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setIdAreaTrabajo($idAreaTrabajo) {
        $this->idAreaTrabajo = $idAreaTrabajo;
    }
    public function setNombreArea($nombreArea) {
        $this->nombreArea = $nombreArea;
    }

    // --- JSON ---
    public function jsonSerialize(): mixed {
        return [
            'idPuestoTrabajo' => $this->idPuestoTrabajo,
            'nombre'          => $this->nombre,
            'idAreaTrabajo'   => $this->idAreaTrabajo,
            'nombreArea'      => $this->nombreArea // Enviamos también el nombre del área
        ];
    }
}
?>