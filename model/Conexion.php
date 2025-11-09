<?php
class Conexion
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $host = "bank";
        $db = "veterinaria";
        $user = "Danny";
        $pass = "TuPassword"; // Cambia esto por tu password real

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            // Primero intenta conectar sin seleccionar BD para verificar credenciales
            $this->conn = new mysqli($host, $user, $pass);
            
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            
            // Seleccionar la base de datos
            if (!$this->conn->select_db($db)) {
                throw new Exception("Error al seleccionar BD: " . $this->conn->error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            error_log("ERROR CONEXION BD: " . $e->getMessage());
            // Mostrar error más específico
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConexion()
    {
        return $this->conn;
    }
    
    // Método para verificar conexión
    public function testConnection()
    {
        try {
            $result = $this->conn->query("SELECT 1");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}