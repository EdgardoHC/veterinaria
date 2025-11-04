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

    // LISTAR
    public function listar()
    {
        try {
            $sql = "SELECT 
                        u.idUsuario AS idusuario,
                        CONCAT(u.nombre, ' ', u.apellidos) AS nombrecompleto,
                        u.apodo AS nombreusuario,
                        u.email AS correoelectronico,
                        u.idrol,
                        u.estado
                    FROM usuarios u";
            $result = $this->conn->query($sql);
            if (!$result) {
                return [];
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error al listar usuarios: " . $e->getMessage());
            return [];
        }
    }

    // CREAR
    public function crear(Usuario $u)
    {
        // nombre, apellidos, email, apodo, pwd, idrol, estado
        $sql = "INSERT INTO usuarios 
                    (nombre, apellidos, email, apodo, pwd, idrol, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt   = $this->conn->prepare($sql);
            $hashed = password_hash($u->getPwd(), PASSWORD_DEFAULT);

            $nombre    = $u->getNombre();
            $apellidos = $u->getApellidos();
            $email     = $u->getEmail();
            $apodo     = $u->getNombreUsuario();
            $idRol     = $u->getIdRol();
            $estado    = $u->getEstado();
            $stmt->bind_param(
                "sssssii",
                $nombre,
                $apellidos,
                $email,
                $apodo,
                $hashed,
                $idRol,
                $estado
            );

            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            throw $e; // que lo capture el controller
        }
    }

    // ACTUALIZAR (simplificado: no tocamos apellidos)
  public function actualizar(Usuario $u)
{
    $sql = "UPDATE usuarios 
            SET nombre = ?, 
                apodo  = ?, 
                email  = ?, 
                idrol  = ?, 
                estado = ?
            WHERE idUsuario = ?";

    try {
        $stmt = $this->conn->prepare($sql);

        //bind_param necesita variables (paso por referencia)
        $nombre    = $u->getNombre();
        $apodo     = $u->getNombreUsuario();
        $email     = $u->getEmail();
        $idRol     = $u->getIdRol();
        $estado    = $u->getEstado();
        $idUsuario = $u->getIdUsuario();

        $stmt->bind_param(
            "sssiii",
            $nombre,
            $apodo,
            $email,
            $idRol,
            $estado,
            $idUsuario
        );

        $stmt->execute();
        return $stmt->affected_rows >= 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Error al actualizar usuario: " . $e->getMessage());
        throw $e;
    }
}


    // ELIMINADO lógico
    public function eliminar($id)
    {
        $sql = "UPDATE usuarios SET estado = 0 WHERE idUsuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->affected_rows >= 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            throw $e;
        }
    }

    // LOGIN: buscar por email o apodo
    public function buscarPorEmailOApodo($usuario)
    {
        $sql = "SELECT u.*, r.nombre AS nombre_rol 
                FROM usuarios u 
                LEFT JOIN rol r ON u.idrol = r.idrol 
                WHERE u.email = ? OR u.apodo = ? 
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
