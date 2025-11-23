<?php
// model/MascotaReportesDAO.php
require_once "Conexion.php";

class MascotaReportesDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /** R2: Lista todas las mascotas, incluyendo datos del dueño y la raza. */
    public function listarMascotasConEncargados()
    {
        try {
            $sql = "
                SELECT 
                    m.idmascota, m.nombres AS nombre_mascota, m.sexo, m.fechanacimiento, m.color,
                    e.nombres AS nombre_encargado, e.apellidos AS apellido_encargado, e.telefonomovil,
                    r.nombre AS nombre_raza
                FROM mascota m
                JOIN encargado e ON m.idencargado = e.idencargado
                JOIN raza r ON m.idraza = r.idraza
                ORDER BY m.nombres
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar mascotas para reportes: " . $e->getMessage());
            return [];
        }
    }

    /** R2: Cuenta la distribución de mascotas por raza (para gráfico). */
    public function contarMascotasPorRaza()
    {
        try {
            $sql = "
                SELECT 
                    r.nombre AS raza_nombre, 
                    COUNT(m.idmascota) AS conteo 
                FROM mascota m
                JOIN raza r ON m.idraza = r.idraza
                GROUP BY r.nombre
                ORDER BY conteo DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al contar mascotas por raza: " . $e->getMessage());
            return [];
        }
    }
    
    /** R7: Clientes Frecuentes. Lista mascotas con el mayor número de visitas. */
    public function listarClientesFrecuentes(int $limite = 10)
    {
        try {
            // NOTA: La tabla 'clientes' no existe en el DDL. Asumo que el "cliente" es el encargado (e).
            // Usamos la tabla 'encargado' (e) para el nombre del cliente/dueño.
            $sql = "
                SELECT 
                    m.nombres AS nombre_mascota, 
                    m.apellidos AS apellido_mascota,
                    e.nombres AS nombre_cliente,
                    e.apellidos AS apellido_cliente,
                    COUNT(exp.idexpediente) AS total_visitas
                FROM mascota m
                JOIN encargado e ON m.idencargado = e.idencargado
                JOIN expediente exp ON m.idmascota = exp.idmascota
                GROUP BY m.idmascota, e.idencargado
                ORDER BY total_visitas DESC
                LIMIT ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $limite);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar clientes frecuentes: " . $e->getMessage());
            return [];
        }
    }
}
?>