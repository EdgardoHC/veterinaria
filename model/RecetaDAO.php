<?php
require_once 'Conexion.php';
require_once 'Receta.php';

class RecetaDAO {
    private $conn;
    
    public function __construct() {
        $conexion = Conexion::getInstance();
        $this->conn = $conexion->getConexion();  // ✅ Usando getConexion()
    }
    
    public function guardarReceta($datos) {
        $query = "INSERT INTO receta (fecha, descripcion, idexpedientedetalle, dosis) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssis", 
            $datos['fecha'],
            $datos['descripcion'],
            $datos['idexpedientedetalle'],
            $datos['dosis']
        );
        
        if ($stmt->execute()) {
            return ['success' => true, 'id' => $stmt->insert_id];
        } else {
            return ['success' => false, 'message' => $stmt->error];
        }
    }
    
    public function obtenerRecetasPorExpediente($idexpedientedetalle) {
        $query = "SELECT r.fecha, r.descripcion, r.dosis 
                  FROM receta r 
                  WHERE r.idexpedientedetalle = ? 
                  ORDER BY r.fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $idexpedientedetalle);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $recetas = [];
        while ($row = $result->fetch_assoc()) {
            $recetas[] = $row;
        }
        
        return $recetas;
    }
}
?>