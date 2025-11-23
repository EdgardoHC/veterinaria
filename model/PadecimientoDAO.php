<?php
require_once "Conexion.php";
require_once "Padecimiento.php";

class PadecimientoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    public function listar()
    {
        $sql = "SELECT idPadecimiento, nombre FROM padecimientos";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // array asociativo
    }

    public function crear(Padecimiento $p)
    {
        $sql = "INSERT INTO padecimientos(nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $nombre = $p->getNombre();
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    public function actualizar(Padecimiento $p)
    {
        $sql = "UPDATE padecimientos SET nombre = ? WHERE idPadecimiento = ?";
        $stmt = $this->conn->prepare($sql);
        $nombre = $p->getNombre();
        $idPadecimiento = $p->getIdPadecimiento();
        $stmt->bind_param("si",$nombre , $idPadecimiento);

        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM padecimientos WHERE idPadecimiento = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT idPadecimiento, nombre FROM padecimientos WHERE idPadecimiento = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // un solo registro
    }
}
