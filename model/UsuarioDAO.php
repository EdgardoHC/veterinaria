<?php
require_once __DIR__ . "/Conexion.php";
require_once __DIR__ . "/Usuario.php";

class UsuarioDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    public function listar()
    {
        try {
            $sql = "SELECT 
                        idusuario as idUsuario, 
                        nombrecompleto as nombre, 
                        '' as apellidos,  
                        correoelectronico as email, 
                        nombreusuario as apodo 
                    FROM usuarios";
            
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar usuarios: " . $e->getMessage());
            return [];
        }
    }

    public function listarVeterinarios() {
        try {
            // Esta funcion es para el Select del Expediente
            $sql = "SELECT 
                        idusuario as id, 
                        nombrecompleto as nombre, 
                        '' as apellidos 
                    FROM usuarios";
            
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar veterinarios: " . $e->getMessage());
            return [];
        }
    }

    public function crear(Usuario $u)
    {
        // la BD requiere idempleado, idrol, etc. 
        // estoy poniendo '1' y '1' por defecto para evitar el error, pero se debe revisar
        $sql = "INSERT INTO usuarios (nombrecompleto, correoelectronico, nombreusuario, contrasena, idempleado, idrol, estado, fechacreacion, contrasenatemporal) 
                VALUES (?, ?, ?, ?, 1, 1, 1, NOW(), '')";

        try {
            $stmt = $this->conn->prepare($sql);
            $hashed = password_hash($u->getPwd(), PASSWORD_DEFAULT);
            
            // Concatenamos nombre y apellido porque la BD solo tiene un campo
            $nombreCompleto = $u->getNombre() . ' ' . $u->getApellidos();

            $stmt->bind_param(
                "ssss",
                $nombreCompleto,
                $u->getEmail(),
                $u->getApodo(),
                $hashed
            );
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(Usuario $u)
    {
        $sql = "UPDATE usuarios SET nombrecompleto = ?, correoelectronico = ?, nombreusuario = ? WHERE idusuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $nombreCompleto = $u->getNombre() . ' ' . $u->getApellidos();
            
            $stmt->bind_param(
                "sssi",
                $nombreCompleto,
                $u->getEmail(),
                $u->getApodo(),
                $u->getIdUsuario()
            );
            $stmt->execute();
            return $stmt->affected_rows >= 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM usuarios WHERE idusuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorEmailOApodo($usuario)
    {
        $sql = "SELECT idusuario, nombrecompleto, nombreusuario, correoelectronico, contrasena 
                FROM usuarios WHERE correoelectronico = ? OR nombreusuario = ? LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $usuario, $usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log("Error al buscar usuario: " . $e->getMessage());
            return null;
        }
    }
}
?>