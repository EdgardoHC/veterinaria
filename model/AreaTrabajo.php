<?php
/**
 * Entidad: Área de Trabajo
 * Representa la tabla 'areadetrabajo'
 */
class AreaTrabajo implements JsonSerializable
{
    // Atributos (deben coincidir con lo que necesitas manejar)
    private $idAreaTrabajo;
    private $nombre;

    // --- Getters (Para obtener valores) ---
    public function getIdAreaTrabajo() {
        return $this->idAreaTrabajo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    // --- Setters (Para asignar valores) ---
    public function setIdAreaTrabajo($idAreaTrabajo) {
        $this->idAreaTrabajo = $idAreaTrabajo;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // --- Serialización JSON ---
    // Esto permite que cuando el controlador haga "json_encode", 
    // el objeto se convierta automáticamente en un JSON limpio para el Frontend.
    public function jsonSerialize(): mixed {
        return [
            'idAreaTrabajo' => $this->idAreaTrabajo,
            'nombre' => $this->nombre
        ];
    }
}
?>