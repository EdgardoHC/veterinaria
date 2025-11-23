<?php
require_once "Conexion.php";
require_once "Auditoria.php";

/**
 * DAO de Auditoría
 * Grupo 6: Seguridad, auditoría y respaldo de datos
 */
class AuditoriaDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /**
     * Registra una acción en la auditoría
     */
    public function registrar(Auditoria $auditoria)
    {
        $sql = "INSERT INTO auditoria (
            idUsuario, accion, tabla, idRegistro, 
            datosAnteriores, datosNuevos, ip, userAgent, fecha
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "isssssss",
                $auditoria->getIdUsuario(),
                $auditoria->getAccion(),
                $auditoria->getTabla(),
                $auditoria->getIdRegistro(),
                $auditoria->getDatosAnteriores(),
                $auditoria->getDatosNuevos(),
                $auditoria->getIp(),
                $auditoria->getUserAgent()
            );
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al registrar auditoría: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista registros de auditoría con filtros opcionales
     */
    public function listar($filtros = [])
    {
        $sql = "SELECT a.*, u.nombre, u.apellidos, u.apodo 
                FROM auditoria a
                LEFT JOIN usuarios u ON a.idUsuario = u.idUsuario
                WHERE 1=1";
        
        $tipos = "";
        $parametros = [];

        if (!empty($filtros['idUsuario'])) {
            $sql .= " AND a.idUsuario = ?";
            $tipos .= "i";
            $parametros[] = $filtros['idUsuario'];
        }

        if (!empty($filtros['accion'])) {
            $sql .= " AND a.accion = ?";
            $tipos .= "s";
            $parametros[] = $filtros['accion'];
        }

        if (!empty($filtros['tabla'])) {
            $sql .= " AND a.tabla = ?";
            $tipos .= "s";
            $parametros[] = $filtros['tabla'];
        }

        if (!empty($filtros['fechaDesde'])) {
            $sql .= " AND a.fecha >= ?";
            $tipos .= "s";
            $parametros[] = $filtros['fechaDesde'];
        }

        if (!empty($filtros['fechaHasta'])) {
            $sql .= " AND a.fecha <= ?";
            $tipos .= "s";
            $parametros[] = $filtros['fechaHasta'];
        }

        $sql .= " ORDER BY a.fecha DESC LIMIT 1000";

        try {
            if (!empty($parametros)) {
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param($tipos, ...$parametros);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->conn->query($sql);
            }
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar auditoría: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene estadísticas de auditoría
     */
    public function obtenerEstadisticas($fechaDesde = null, $fechaHasta = null)
    {
        $sql = "SELECT 
                    accion,
                    COUNT(*) as total,
                    COUNT(DISTINCT idUsuario) as usuariosUnicos
                FROM auditoria
                WHERE 1=1";

        $tipos = "";
        $parametros = [];

        if ($fechaDesde) {
            $sql .= " AND fecha >= ?";
            $tipos .= "s";
            $parametros[] = $fechaDesde;
        }

        if ($fechaHasta) {
            $sql .= " AND fecha <= ?";
            $tipos .= "s";
            $parametros[] = $fechaHasta;
        }

        $sql .= " GROUP BY accion ORDER BY total DESC";

        try {
            if (!empty($parametros)) {
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param($tipos, ...$parametros);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->conn->query($sql);
            }
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [];
        }
    }
}

