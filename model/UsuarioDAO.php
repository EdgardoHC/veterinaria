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
            $idempleado     = $u->getIdEmpleado();
            $contrasenaTemporal = ""; // Vacío por ahora

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

    public function actualizar(Usuario $u)
    {
        $sql = "UPDATE usuarios 
                SET nombrecompleto = ?, 
                    nombreusuario  = ?, 
                    correoelectronico = ?, 
                    idrol = ?, 
                    estado = ?";
        
        $params = [];
        $types = ""; 

        $params[] = $u->getNombre();
        $types .= "s";
        $params[] = $u->getNombreUsuario();
        $types .= "s";
        $params[] = $u->getEmail();
        $types .= "s";
        $params[] = $u->getIdRol();
        $types .= "i";
        $params[] = $u->getEstado();
        $types .= "i";

        $pwd = $u->getPwd();
        if (!empty($pwd)) {
            $sql .= ", contrasena = ?"; 
            $params[] = password_hash($pwd, PASSWORD_DEFAULT); 
            $types .= "s";
        }
        $sql .= " WHERE idusuario = ?";
        $params[] = $u->getIdUsuario();
        $types .= "i";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params); 
            
            $stmt->execute();
            return $stmt->affected_rows >= 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            throw $e;
        }
    }

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

    public function buscarPorEmailONombreUsuario($login)
    {
        $sql = "SELECT u.*, r.nombre AS nombre_rol 
                FROM usuarios u 
                LEFT JOIN rol r ON u.idrol = r.idrol 
                WHERE (u.correoelectronico = ? OR u.nombreusuario = ?)
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
    public function actualizarContrasena($idUsuario, $pwdPlano)
    {
        $hashed = password_hash($pwdPlano, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ? WHERE idusuario = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $hashed, $idUsuario);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al migrar hash: " . $e->getMessage());
            return false; 
        }
    }

} 
