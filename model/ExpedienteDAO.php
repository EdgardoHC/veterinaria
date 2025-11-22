<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/Expediente.php';

class ExpedienteDAO {
    private $conn;
    
    public function __construct() {
        $conexion = Conexion::getInstance();
        $this->conn = $conexion->getConexion();  
    }
    
    // 1. BUSCAR EXPEDIENTE
    public function buscarExpediente($busqueda) {
        // Si es número, buscamos por ID Expediente, si no, por Nombre Mascota
        if (is_numeric($busqueda)) {
            $sql = "SELECT e.idexpediente, e.idmascota, 
                           m.nombres as nombre_mascota, m.sexo, m.color,
                           r.nombre as raza,
                           CONCAT(enc.nombres, ' ', enc.apellidos) as encargado_nombre,
                           '' as encargado_apellido 
                    FROM expediente e
                    INNER JOIN mascota m ON e.idmascota = m.idmascota
                    INNER JOIN raza r ON m.idraza = r.idraza
                    INNER JOIN encargado enc ON m.idencargado = enc.idencargado
                    WHERE e.idexpediente = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $busqueda);
        } else {
            $sql = "SELECT e.idexpediente, e.idmascota, 
                           m.nombres as nombre_mascota, m.sexo, m.color,
                           r.nombre as raza,
                           CONCAT(enc.nombres, ' ', enc.apellidos) as encargado_nombre,
                           '' as encargado_apellido 
                    FROM expediente e
                    INNER JOIN mascota m ON e.idmascota = m.idmascota
                    INNER JOIN raza r ON m.idraza = r.idraza
                    INNER JOIN encargado enc ON m.idencargado = enc.idencargado
                    WHERE m.nombres LIKE ?"; // OJO: 'nombres' en plural
            $stmt = $this->conn->prepare($sql);
            $term = "%$busqueda%";
            $stmt->bind_param("s", $term);
        }

        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            return ['success' => true, 'data' => $row];
        } else {
            return ['success' => false, 'message' => 'Expediente no encontrado'];
        }
    }

    // 2. GUARDAR CONSULTA (Detalle)
    public function guardarConsulta($datos) {
        $sql = "INSERT INTO expedientedetalle (idexpediente, fecha, peso, altura, resumen, diagnostico, idusuario) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return ['success' => false, 'message' => $this->conn->error];

        $stmt->bind_param("isddssi", 
            $datos['idexpediente'],
            $datos['fecha'],
            $datos['peso'],
            $datos['altura'],
            $datos['resumen'],
            $datos['diagnostico'],
            $datos['idusuario']
        );

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $stmt->insert_id];
        } else {
            return ['success' => false, 'message' => $stmt->error];
        }
    }

    // 3. OBTENER HISTORIAL
    public function obtenerHistorialMascota($idExpediente) {
        // Traemos las últimas consultas de este expediente
        $sql = "SELECT d.fecha, d.peso, d.altura, d.diagnostico, u.nombrecompleto as veterinario
                FROM expedientedetalle d
                LEFT JOIN usuarios u ON d.idusuario = u.idusuario
                WHERE d.idexpediente = ?
                ORDER BY d.fecha DESC LIMIT 5";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idExpediente);
        $stmt->execute();
        $res = $stmt->get_result();
        
        return $res->fetch_all(MYSQLI_ASSOC);
    }
public function createExpediente($idMascota, $fecha, $descripcion, $idVeterinario) {
        $sql = "INSERT INTO expediente (idmascota, fecha, descripcion, idusuario) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error prepare: " . $this->conn->error);
        }

        $stmt->bind_param("issi", $idMascota, $fecha, $descripcion, $idVeterinario);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            throw new Exception("Error execute: " . $stmt->error);
        }
    }

}
?>