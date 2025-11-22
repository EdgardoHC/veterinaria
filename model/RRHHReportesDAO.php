<?php
// model/RRHHReportesDAO.php
require_once "Conexion.php";

class RRHHReportesDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /** R10: Lista los empleados con su puesto y área de trabajo. */
    public function listarEmpleadosDetalle()
    {
        try {
            $sql = "
                SELECT 
                    e.idempleado, e.nombres, e.apellidos, e.dni, e.telefonocelular, e.fechaingreso, e.correoelectronico,
                    pdt.nombre AS puesto,
                    adt.nombre AS area
                FROM empleado e
                JOIN puestodetrabajo pdt ON e.idpuestodetrabajo = pdt.idpuestodetrabajo
                JOIN areadetrabajo adt ON pdt.idareadetrabajo = adt.idareadetrabajo
                ORDER BY e.apellidos, e.nombres
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar empleados con detalle: " . $e->getMessage());
            return [];
        }
    }

    /** R11: Distribución por Área. Cuenta los empleados agrupados por área. */
    public function contarEmpleadosPorArea()
    {
        try {
            $sql = "
                SELECT 
                    adt.nombre AS area_nombre, 
                    COUNT(e.idempleado) AS conteo 
                FROM empleado e
                JOIN puestodetrabajo pdt ON e.idpuestodetrabajo = pdt.idpuestodetrabajo
                JOIN areadetrabajo adt ON pdt.idareadetrabajo = adt.idareadetrabajo
                GROUP BY adt.nombre
                ORDER BY conteo DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al contar empleados por área: " . $e->getMessage());
            return [];
        }
    }
}
?>