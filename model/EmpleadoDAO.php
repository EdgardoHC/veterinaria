<?php
require_once "Conexion.php";

class EmpleadoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

  // Empleados sin usuario asignado
public function listarEmpleadosSinUsuario()
{
    $sql = "
        SELECT 
            e.idempleado,
            TRIM(CONCAT_WS(' ', e.nombres, e.apellidos)) AS nombreCompleto
        FROM empleado e
        LEFT JOIN usuarios u 
            ON u.idempleado = e.idempleado
        WHERE u.idempleado IS NULL
        ORDER BY e.apellidos, e.nombres
    ";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $data;
    } catch (mysqli_sql_exception $e) {
        error_log("Error listarEmpleadosSinUsuario: " . $e->getMessage());
        return [];
    }
}

// Empleados para edición de usuario (incluye el empleado del usuario actual)
public function listarEmpleadosParaEdicion(int $idusuario)
{
    $sql = "
        SELECT 
            e.idempleado,
            TRIM(CONCAT_WS(' ', e.nombres, e.apellidos)) AS nombreCompleto
        FROM empleado e
        WHERE NOT EXISTS (
            SELECT 1 
            FROM usuarios u
            WHERE u.idempleado = e.idempleado
              AND u.idusuario <> ?
        )
        ORDER BY e.apellidos, e.nombres";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $data;
    } catch (mysqli_sql_exception $e) {
        error_log("Error listarEmpleadosParaEdicion: " . $e->getMessage());
        return [];
    }
}

}