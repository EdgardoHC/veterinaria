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
                        u.idusuario,
                        u.nombrecompleto,
                        u.nombreusuario,
                        u.correoelectronico,
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
        

        $sql = "INSERT INTO usuarios 
                    (nombrecompleto,
                     nombreusuario,
                     correoelectronico,
                     contrasena,
                     idempleado,
                     contrasenatemporal,
                     estado,
                     fechacreacion,
                     idrol) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

        try {
            $stmt   = $this->conn->prepare($sql);
            $hashed = password_hash($u->getPwd(), PASSWORD_DEFAULT);

            $nombreCompleto = $u->getNombre();      
            $nombreUsuario  = $u->getNombreUsuario();   
            $email          = $u->getEmail();
            $idRol          = $u->getIdRol();
            $estado         = $u->getEstado();

            $idempleado = 1;       

            $contrasenaTemporal = "";

            
            $stmt->bind_param(
                "ssssissi",
                $nombreCompleto,
                $nombreUsuario,
                $email,
                $hashed,
                $idempleado,
                $contrasenaTemporal,
                $estado,
                $idRol
            );

            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            throw $e; 
        }
    }

    // ACTUALIZAR
    public function actualizar(Usuario $u)
    {
        $sql = "UPDATE usuarios 
                SET nombrecompleto = ?, 
                    nombreusuario  = ?, 
                    correoelectronico = ?, 
                    idrol = ?, 
                    estado = ?
                WHERE idusuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);

            $nombre    = $u->getNombre();          
            $usuario   = $u->getNombreUsuario();   
            $email     = $u->getEmail();
            $idRol     = $u->getIdRol();
            $estado    = $u->getEstado();
            $idUsuario = $u->getIdUsuario();

            $stmt->bind_param(
                "sssiii",
                $nombre,
                $usuario,
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
        $sql = "UPDATE usuarios SET estado = 0 WHERE idusuario = ?";

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

    // LOGIN: buscar por email o nombre de usuario
    public function buscarPorEmailONombreUsuario($login)
    {
        $sql = "SELECT u.*, r.nombre AS nombre_rol 
                FROM usuarios u 
                LEFT JOIN rol r ON u.idrol = r.idrol 
                WHERE u.correoelectronico = ? OR u.nombreusuario = ? 
                LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $login, $login);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log("Error al buscar usuario: " . $e->getMessage());
            return null;
        }
    }
}
