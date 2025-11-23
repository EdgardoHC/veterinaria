<?php
require_once "Conexion.php";
require_once "Raza.php";

class RazaDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    public function listar()
    {
        $sql = "SELECT idRaza, nombre FROM raza";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function crear(Raza $r)
    {
        $sql = "INSERT INTO raza(nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $nombre = $r->getNombre();
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    public function actualizar(Raza $r)
    {
        $sql = "UPDATE raza SET nombre = ? WHERE idRaza = ?";
        $stmt = $this->conn->prepare($sql);
        $nombre = $r->getNombre();
        $idPadecimiento = $r->getIdRaza();
        $stmt->bind_param("si",$nombre , $idPadecimiento);

        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM raza WHERE idRaza = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT idRaza, nombre FROM raza WHERE idRaza = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
