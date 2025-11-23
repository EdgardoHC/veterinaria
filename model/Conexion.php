<?php
class Conexion {
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $host = "localhost";
        $db = "veterinaria";
        $user = "root";
        $pass = "";

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli($host, $user, $password, $db);
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Error de conexión DB: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Conexion();
        }
        return self::$instance;
    }

    public function getConexion() {
        return $this->conn;
    }
}
?>