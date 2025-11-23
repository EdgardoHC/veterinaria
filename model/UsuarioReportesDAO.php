<?php
// model/UsuarioReportesDAO.php
require_once "Conexion.php";

class UsuarioReportesDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /** R1: Lista de Usuarios con el nombre del Rol. */
    public function listarUsuariosConRol()
    {
        try {
            $sql = "
                SELECT 
                    u.idusuario, 
                    u.nombrecompleto, 
                    u.correoelectronico, 
                    u.nombreusuario, 
                    r.nombre AS nombre_rol,
                    u.idrol
                FROM usuarios u
                JOIN rol r ON u.idrol = r.idrol
                ORDER BY u.nombrecompleto
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar usuarios para reportes: " . $e->getMessage());
            return [];
        }
    }

    /** R1: Conteo de usuarios agrupados por nombre de rol (para gráficos). */
    public function contarUsuariosPorRol()
    {
        try {
            $sql = "
                SELECT 
                    r.nombre AS rol_nombre, 
                    COUNT(u.idusuario) AS conteo 
                FROM usuarios u
                JOIN rol r ON u.idrol = r.idrol
                GROUP BY r.nombre
                ORDER BY conteo DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al contar usuarios por rol para gráfico: " . $e->getMessage());
            return [];
        }
    }
}
?>