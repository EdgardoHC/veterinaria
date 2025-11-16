<?php
require_once __DIR__ . "/Conexion.php";
 
class CartillaModel
{
    private $conn;
 
    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }
 
    public function obtenerMascotas()
    {
        $sql = "SELECT idmascota FROM mascota";
        $res = $this->conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }
 
    public function obtenerUsuarios()
    {
        $sql = "SELECT idusuario FROM usuarios";
        $res = $this->conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }
 
    public function obtenerCartillas()
    {
        $sql = "SELECT idcartillavacunacion, idmascota, fecha, peso, altura, idusuario
                FROM cartillavacunacion ORDER BY idcartillavacunacion DESC";
        $res = $this->conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }
 
    public function agregarCartilla($idmascota, $fecha, $peso, $altura, $idusuario)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO cartillavacunacion (idmascota, fecha, peso, altura, idusuario)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) return false;
        $stmt->bind_param("isddi", $idmascota, $fecha, $peso, $altura, $idusuario);
        return $stmt->execute();
    }
 
    public function editarCartilla($id, $idmascota, $fecha, $peso, $altura, $idusuario)
    {
        $stmt = $this->conn->prepare("
            UPDATE cartillavacunacion
            SET idmascota = ?, fecha = ?, peso = ?, altura = ?, idusuario = ?
            WHERE idcartillavacunacion = ?
        ");
        if (!$stmt) return false;
        $stmt->bind_param("isddii", $idmascota, $fecha, $peso, $altura, $idusuario, $id);
        return $stmt->execute();
    }
 
    public function eliminarCartilla($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM cartillavacunacion WHERE idcartillavacunacion = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
 