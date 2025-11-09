<?php
require_once 'Conexion.php';
require_once 'Expediente.php';

class ExpedienteDAO {
    private $conn;
    
    public function __construct() {
        $conexion = Conexion::getInstance();
        $this->conn = $conexion->getConexion();  
    }
    
    public function buscarExpediente($busqueda) {
        // Si es numérico, buscar por ID, sino por nombre
        if (is_numeric($busqueda)) {
            $query = "SELECT e.idexpediente, m.idmascota, m.nombres as nombre_mascota, 
                             m.fechanacimiento, r.nombre as raza, m.sexo, m.color,
                             enc.nombres as encargado_nombre, enc.apellidos as encargado_apellido
                      FROM expediente e
                      INNER JOIN mascota m ON e.idmascota = m.idmascota
                      INNER JOIN raza r ON m.idraza = r.idraza
                      INNER JOIN encargado enc ON m.idencargado = enc.idencargado
                      WHERE e.idexpediente = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $busqueda);
        } else {
            $query = "SELECT e.idexpediente, m.idmascota, m.nombres as nombre_mascota, 
                             m.fechanacimiento, r.nombre as raza, m.sexo, m.color,
                             enc.nombres as encargado_nombre, enc.apellidos as encargado_apellido
                      FROM expediente e
                      INNER JOIN mascota m ON e.idmascota = m.idmascota
                      INNER JOIN raza r ON m.idraza = r.idraza
                      INNER JOIN encargado enc ON m.idencargado = enc.idencargado
                      WHERE m.nombres LIKE ? OR m.apellidos LIKE ?";
            
            $stmt = $this->conn->prepare($query);
            $likeBusqueda = "%$busqueda%";
            $stmt->bind_param("ss", $likeBusqueda, $likeBusqueda);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return [
                'success' => true,
                'data' => $result->fetch_assoc()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se encontró el expediente'
            ];
        }
    }
    
    public function guardarConsulta($datos) {
        $query = "INSERT INTO expedientedetalle 
                  (fecha, resumen, diagnostico, idexpediente, peso, altura, idusuario) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssdddi", 
            $datos['fecha'],
            $datos['resumen'],
            $datos['diagnostico'],
            $datos['idexpediente'],
            $datos['peso'],
            $datos['altura'],
            $datos['idusuario']
        );
        
        if ($stmt->execute()) {
            return ['success' => true, 'id' => $stmt->insert_id];
        } else {
            return ['success' => false, 'message' => $stmt->error];
        }
    }
    
    public function obtenerHistorialMascota($idMascota) {
        $query = "SELECT ed.fecha, ed.resumen, ed.diagnostico, ed.peso, ed.altura,
                         u.nombrecompleto as veterinario
                  FROM expedientedetalle ed
                  INNER JOIN expediente e ON ed.idexpediente = e.idexpediente
                  INNER JOIN usuarios u ON ed.idusuario = u.idusuario
                  WHERE e.idmascota = ?
                  ORDER BY ed.fecha DESC
                  LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $idMascota);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $historial = [];
        while ($row = $result->fetch_assoc()) {
            $historial[] = $row;
        }
        
        return $historial;
    }
}
?>