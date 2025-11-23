<?php
require_once 'Conexion.php';
require_once 'AreaTrabajo.php';

class AreaTrabajoDAO
{
    private $conn;

    public function __construct()
    {
        // Obtenemos la conexión del Singleton
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /**
     * LISTAR: Devuelve todas las áreas para llenar la tabla
     */
    public function listar()
    {
        $sql = "SELECT idareadetrabajo, nombre FROM areadetrabajo ORDER BY idareadetrabajo ASC";
        $result = $this->conn->query($sql);
        $lista = [];

        while ($row = $result->fetch_assoc()) {
            $area = new AreaTrabajo();
            $area->setIdAreaTrabajo($row['idareadetrabajo']);
            $area->setNombre($row['nombre']);
            $lista[] = $area;
        }
        return $lista;
    }

    /**
     * GUARDAR: Inserta un nuevo registro
     */
    public function guardar(AreaTrabajo $area)
    {
        $sql = "INSERT INTO areadetrabajo (nombre) VALUES (?)";
        try {
            $stmt = $this->conn->prepare($sql);
            $nombre = $area->getNombre();
            $stmt->bind_param("s", $nombre); // "s" indica que es string
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * OBTENER: Busca uno solo por ID (Para editarlo luego)
     */
    public function obtener($id)
    {
        $sql = "SELECT idareadetrabajo, nombre FROM areadetrabajo WHERE idareadetrabajo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id); // "i" indica integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $area = new AreaTrabajo();
            $area->setIdAreaTrabajo($row['idareadetrabajo']);
            $area->setNombre($row['nombre']);
            return $area;
        }
        return null;
    }

    /**
     * ACTUALIZAR: Modifica un registro existente
     */
    public function actualizar(AreaTrabajo $area)
    {
        $sql = "UPDATE areadetrabajo SET nombre = ? WHERE idareadetrabajo = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $nombre = $area->getNombre();
            $id = $area->getIdAreaTrabajo();
            $stmt->bind_param("si", $nombre, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * ELIMINAR: Borra un registro
     */
    public function eliminar($id)
    {
        $sql = "DELETE FROM areadetrabajo WHERE idareadetrabajo = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            // Retorna false si falla (ej. si hay llaves foráneas usándolo)
            return false;
        }
    }
}
?>