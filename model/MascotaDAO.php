<?php
require_once 'Conexion.php';

class MascotaDAO {
    private $conn;
    
    public function __construct() {
        $conexion = Conexion::getInstance();
        $this->conn = $conexion->getConexion();  
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