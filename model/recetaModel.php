<?php
require_once __DIR__ . "/Conexion.php";
 
class RecetaModel
{
    private $conn;
 
    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }
 
    public function obtenerExpedientes()
    {
        $sql = "SELECT idexpedientedetalle FROM expedientedetalle";
        $res = $this->conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }
 
    public function obtenerRecetas()
    {
        $sql = "SELECT idreceta, fecha, descripcion, idexpedientedetalle, dosis
                FROM receta ORDER BY idreceta DESC";
        $res = $this->conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }
 
    //Agregar recetas nuevas
    public function agregarReceta($fecha, $descripcion, $idexpediente, $dosis)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO receta (fecha, descripcion, idexpedientedetalle, dosis)
            VALUES (?, ?, ?, ?)
        ");
 
        if (!$stmt) return false;
 
        $stmt->bind_param("ssis", $fecha, $descripcion, $idexpediente, $dosis);
        return $stmt->execute();
    }
 
    //Editar recetas
    public function editarReceta($id, $fecha, $descripcion, $idexpediente, $dosis)
    {
        $stmt = $this->conn->prepare("
            UPDATE receta
            SET fecha = ?, descripcion = ?, idexpedientedetalle = ?, dosis = ?
            WHERE idreceta = ?
        ");
 
        if (!$stmt) return false;
 
        $stmt->bind_param("ssisi", $fecha, $descripcion, $idexpediente, $dosis, $id);
        return $stmt->execute();
    }
 
    //Eliminar recetas
    public function eliminarReceta($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM receta WHERE idreceta = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
 
 