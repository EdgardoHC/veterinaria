<?php
class Conexion
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $host = "localhost";
        $db = "db_veterinaria";
        $user = "user_veterinaria";
        $pass = "tu_contraseña_segura";

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli($host, $user, $pass, $db);
            $this->conn->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $e) {
            error_log("Error de conexion a la base de datos: " . $e->getMessage());
            throw $e;
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
}
