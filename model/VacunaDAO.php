<?php
require_once "Conexion.php";
require_once "Vacuna.php";

class VacunaDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    public function listar()
    {
        $sql = "SELECT idvacuna, nombre FROM vacunas";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function crear(Vacuna $v)
    {
        $sql = "INSERT INTO vacunas(nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $nombre = $v->getNombre();
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    public function actualizar(Vacuna $v)
    {
        $sql = "UPDATE vacunas SET nombre = ? WHERE idvacuna = ?";
        $stmt = $this->conn->prepare($sql);

        $nombre = $v->getNombre();
        $id = $v->getIdVacuna();

        $stmt->bind_param("si", $nombre, $id);

        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM vacunas WHERE idvacuna = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT idvacuna, nombre FROM vacunas WHERE idvacuna = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
