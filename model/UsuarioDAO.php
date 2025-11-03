<?php
require_once "Conexion.php";
require_once "Usuario.php";

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
            $sql = "SELECT idusuario, nombrecompleto, nombreusuario, correoelectronico, idrol, estado FROM usuarios";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar usuarios: " . $e->getMessage());
            return [];
        }
    }

    public function crear(Usuario $u)
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, apodo, pwd) VALUES (?, ?, ?, ?, ?)";

        try {
            $stmt = $this->conn->prepare($sql);
            $hashed = password_hash($u->getPwd(), PASSWORD_DEFAULT);
            $stmt->bind_param(
                "sssss",
                $u->getNombre(),
                //$u->getApellidos(),
                $u->getEmail(),
                //$u->getApodo(),
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
        $sql = "UPDATE usuarios SET nombrecompleto = ?, nombreusuario = ?, correoelectronico = ?, idrol = ?, estado=? WHERE idusuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "sssiii",
                $u->getNombre(),
                $u->getNombreUsuario(),
                $u->getEmail(),
                $u->getIdRol(),
                $u->getEstado(),
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
        $sql = "UPDATE usuarios SET estado=0 WHERE idusuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "i",
                $id
            );
            $stmt->execute();
            return $stmt->affected_rows >= 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorEmailOApodo($usuario)
    {
        $sql = "SELECT u.*, r.nombre AS nombre_rol 
                FROM usuarios u 
                LEFT JOIN rol r ON u.idrol = r.idrol 
                WHERE u.correoelectronico = ? OR u.nombreusuario = ? 
                LIMIT 1";

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
