<?php
require_once __DIR__ . '/Conexion.php';

class MascotaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    public function listarMascotas() {
        $sql = "SELECT idMascota as id, nombres FROM mascota ORDER BY nombres ASC";
        
        $result = $this->conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }


    
    public function calcularEdad($fechaNacimiento) {
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($nacimiento);
        
        if ($diferencia->y > 0) {
            return $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '');
        } elseif ($diferencia->m > 0) {
            return $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
        } else {
            return $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
        }
    }
}
?>
