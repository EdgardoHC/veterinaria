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
            datosAnteriores, datosNuevos, ipAddress, userAgent, fecha
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        try {
            $stmt = $this->conn->prepare($sql);
            
            // Corregido: Asignar los valores a variables antes de bind_param
            $idUsuario = $auditoria->getIdUsuario();
            $accion = $auditoria->getAccion();
            $tabla = $auditoria->getTabla();
            $idRegistro = $auditoria->getIdRegistro();
            $datosAnteriores = $auditoria->getDatosAnteriores();
            $datosNuevos = $auditoria->getDatosNuevos();
            $ip = $auditoria->getIp();
            $userAgent = $auditoria->getUserAgent();

            $stmt->bind_param(
                "isssssss",
                $idUsuario,
                $accion,
                $tabla,
                $idRegistro,
                $datosAnteriores,
                $datosNuevos,
                $ip,
                $userAgent
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
        // CORRECCIÓN: Se usa u.nombreUsuario en lugar de u.apodo
        $sql = "SELECT a.*, u.nombre, u.apellidos, u.nombreUsuario 
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
                // CÓDIGO CORREGIDO: Se usa call_user_func_array para evitar el Notice de PHP 8
                call_user_func_array(array($stmt, 'bind_param'), array_merge(array($tipos), $parametros));
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
                // CÓDIGO CORREGIDO: Se usa call_user_func_array para evitar el Notice de PHP 8
                call_user_func_array(array($stmt, 'bind_param'), array_merge(array($tipos), $parametros));
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