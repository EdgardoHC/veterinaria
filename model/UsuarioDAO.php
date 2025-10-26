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
            $sql = "SELECT idUsuario, nombre, apellidos, email, apodo FROM usuarios";
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
                $u->getApellidos(),
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
        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?, apodo = ? WHERE idUsuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssi",
                $u->getNombre(),
                $u->getApellidos(),
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
        $sql = "DELETE FROM usuarios WHERE idUsuario = ?";

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
        $sql = "SELECT * FROM usuarios WHERE email = ? OR apodo = ? LIMIT 1";

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
