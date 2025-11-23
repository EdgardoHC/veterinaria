<?php
require_once 'Conexion.php';
require_once 'PuestoTrabajo.php';

class PuestoTrabajoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /**
     * LISTAR: Trae los puestos y el nombre del área a la que pertenecen
     */
    public function listar()
    {
        // Hacemos JOIN para obtener el nombre del área en lugar de solo el ID
        $sql = "SELECT p.idpuestodetrabajo, p.nombre, p.idareadetrabajo, a.nombre as nombre_area
                FROM puestodetrabajo p
                INNER JOIN areadetrabajo a ON p.idareadetrabajo = a.idareadetrabajo
                ORDER BY p.nombre ASC";
        
        $result = $this->conn->query($sql);
        $lista = [];

        while ($row = $result->fetch_assoc()) {
            $puesto = new PuestoTrabajo();
            $puesto->setIdPuestoTrabajo($row['idpuestodetrabajo']);
            $puesto->setNombre($row['nombre']);
            $puesto->setIdAreaTrabajo($row['idareadetrabajo']);
            
            // Asignamos el nombre del área a la propiedad auxiliar
            $puesto->setNombreArea($row['nombre_area']);
            
            $lista[] = $puesto;
        }
        return $lista;
    }

    /**
     * GUARDAR
     */
    public function guardar(PuestoTrabajo $puesto)
    {
        $sql = "INSERT INTO puestodetrabajo (nombre, idareadetrabajo) VALUES (?, ?)";
        try {
            $stmt = $this->conn->prepare($sql);
            $nombre = $puesto->getNombre();
            $idArea = $puesto->getIdAreaTrabajo();
            
            // "si" = string, integer
            $stmt->bind_param("si", $nombre, $idArea);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * OBTENER
     */
    public function obtener($id)
    {
        $sql = "SELECT idpuestodetrabajo, nombre, idareadetrabajo FROM puestodetrabajo WHERE idpuestodetrabajo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $puesto = new PuestoTrabajo();
            $puesto->setIdPuestoTrabajo($row['idpuestodetrabajo']);
            $puesto->setNombre($row['nombre']);
            $puesto->setIdAreaTrabajo($row['idareadetrabajo']);
            return $puesto;
        }
        return null;
    }

    /**
     * ACTUALIZAR
     */
    public function actualizar(PuestoTrabajo $puesto)
    {
        $sql = "UPDATE puestodetrabajo SET nombre = ?, idareadetrabajo = ? WHERE idpuestodetrabajo = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $nombre = $puesto->getNombre();
            $idArea = $puesto->getIdAreaTrabajo();
            $id = $puesto->getIdPuestoTrabajo();
            
            $stmt->bind_param("sii", $nombre, $idArea, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * ELIMINAR
     */
    public function eliminar($id)
    {
        $sql = "DELETE FROM puestodetrabajo WHERE idpuestodetrabajo = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>