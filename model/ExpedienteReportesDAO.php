<?php
// model/ExpedienteReportesDAO.php

require_once "Conexion.php";

class ExpedienteReportesDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    /** * R4: Lista las próximas vacunas pendientes.
     * CÓDIGO CORREGIDO: Muestra TODO el historial para fines de prueba.
     */
    public function listarProximasVacunas()
    {
        try {
            $sql = "
                SELECT 
                    cd.fechaproximavacuna, cd.numerorefuerzo,
                    v.nombre AS nombre_vacuna,
                    m.nombres AS nombre_mascota, m.apellidos AS apellido_mascota,
                    e.nombres AS nombre_encargado, e.telefonomovil
                FROM cartillavacunaciondetalle cd
                JOIN cartillavacunacion c ON cd.idcartillavacunacion = c.idcartillavacunacion
                JOIN mascota m ON c.idmascota = m.idmascota
                JOIN encargado e ON m.idencargado = e.idencargado
                JOIN vacunas v ON cd.idvacuna = v.idvacuna
                ORDER BY cd.fechaproximavacuna DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar próximas vacunas: " . $e->getMessage());
            return [];
        }
    }
    
    /** * R4: Cuenta la distribución de citas por tipo de vacuna.
     * CÓDIGO CORREGIDO: Cuenta todo el historial para el gráfico.
     */
    public function contarCitasPorVacuna()
    {
        try {
            $sql = "
                SELECT 
                    v.nombre AS vacuna_nombre, 
                    COUNT(cd.idcartillavacunaciondetalle) AS conteo 
                FROM cartillavacunaciondetalle cd
                JOIN vacunas v ON cd.idvacuna = v.idvacuna
                GROUP BY v.nombre
                ORDER BY conteo DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al contar citas por vacuna: " . $e->getMessage());
            return [];
        }
    }

    /** R6: Lista los detalles de cada consulta médica (Expediente Detalle). */
    public function listarDetallesExpediente()
    {
        try {
            $sql = "
                SELECT 
                    ed.fecha, ed.resumen, ed.diagnostico, ed.peso, ed.altura,
                    e.descripcion AS tipo_expediente, 
                    m.nombres AS nombre_mascota, m.apellidos AS apellido_mascota,
                    u.nombrecompleto AS veterinario_nombre
                FROM expedientedetalle ed
                JOIN expediente e ON ed.idexpediente = e.idexpediente
                JOIN mascota m ON e.idmascota = m.idmascota
                JOIN usuarios u ON ed.idusuario = u.idusuario
                ORDER BY ed.fecha DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar detalles de expedientes: " . $e->getMessage());
            return [];
        }
    }

    /** R8: Padecimientos Comunes. Cuenta los padecimientos diagnosticados (Gráfico). */
    public function contarPadecimientosComunes()
    {
        try {
            $sql = "
                SELECT 
                    p.nombre AS nombre_padecimiento, 
                    COUNT(pd.idpadecede) AS conteo 
                FROM padecede pd
                JOIN padecimientos p ON pd.idpadecimiento = p.idpadecimiento
                GROUP BY p.nombre
                ORDER BY conteo DESC
            ";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al contar padecimientos comunes: " . $e->getMessage());
            return [];
        }
    }
}
?>