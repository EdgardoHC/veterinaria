<?php
require_once "Conexion.php";
require_once "Encargado.php";

class EncargadoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
    }

    public function listar()
    {
        $sql = "SELECT 
                    idencargado,
                    nombres,
                    apellidos,
                    telefonofijo,
                    telefonomovil,
                    dni,
                    direccion,
                    correoelectronico,
                    fechanacimiento,
                    sexo
                FROM encargado";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function crear(Encargado $e)
    {
        $sql = "INSERT INTO encargado
                (nombres, apellidos, telefonofijo, telefonomovil, dni, direccion, correoelectronico, fechanacimiento, sexo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $nombres          = $e->getNombres();
        $apellidos        = $e->getApellidos();
        $telefonofijo     = $e->getTelefonoFijo();
        $telefonomovil    = $e->getTelefonoMovil();
        $dni              = $e->getDni();
        $direccion        = $e->getDireccion();
        $correo           = $e->getCorreoElectronico();
        $fechanacimiento  = $e->getFechaNacimiento();
        $sexo             = $e->getSexo();


        $stmt->bind_param(
            "sssssssss",
            $nombres,
            $apellidos,
            $telefonofijo,
            $telefonomovil,
            $dni,
            $direccion,
            $correo,
            $fechanacimiento,
            $sexo
        );

        return $stmt->execute();
    }

    public function actualizar(Encargado $e)
    {
        $sql = "UPDATE encargado
                SET nombres = ?,
                    apellidos = ?,
                    telefonofijo = ?,
                    telefonomovil = ?,
                    dni = ?,
                    direccion = ?,
                    correoelectronico = ?,
                    fechanacimiento = ?,
                    sexo = ?
                WHERE idencargado = ?";
        $stmt = $this->conn->prepare($sql);

        $nombres          = $e->getNombres();
        $apellidos        = $e->getApellidos();
        $telefonofijo     = $e->getTelefonoFijo();
        $telefonomovil    = $e->getTelefonoMovil();
        $dni              = $e->getDni();
        $direccion        = $e->getDireccion();
        $correo           = $e->getCorreoElectronico();
        $fechanacimiento  = $e->getFechaNacimiento();
        $sexo             = $e->getSexo();
        $id               = $e->getIdEncargado();

        // 9 strings + 1 int
        $stmt->bind_param(
            "sssssssssi",
            $nombres,
            $apellidos,
            $telefonofijo,
            $telefonomovil,
            $dni,
            $direccion,
            $correo,
            $fechanacimiento,
            $sexo,
            $id
        );

        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM encargado WHERE idencargado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT 
                    idencargado,
                    nombres,
                    apellidos,
                    telefonofijo,
                    telefonomovil,
                    dni,
                    direccion,
                    correoelectronico,
                    fechanacimiento,
                    sexo
                FROM encargado
                WHERE idencargado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
