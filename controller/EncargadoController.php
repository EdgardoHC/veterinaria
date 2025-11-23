<?php
require_once "../model/EncargadoDAO.php";

$dao = new EncargadoDAO();

header('Content-Type: application/json; charset=utf-8');

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case "listar":
            echo json_encode($dao->listar());
            break;

        case "crear":
            $e = new Encargado();
            $e->setNombres($_POST['nombres']);
            $e->setApellidos($_POST['apellidos']);
            $e->setTelefonoFijo($_POST['telefonofijo'] ?? null);
            $e->setTelefonoMovil($_POST['telefonomovil']);
            $e->setDni($_POST['dni'] ?? null);
            $e->setDireccion($_POST['direccion'] ?? null);
            $e->setCorreoElectronico($_POST['correoelectronico']);
            $e->setFechaNacimiento($_POST['fechanacimiento'] ?? null);
            $e->setSexo($_POST['sexo'] ?? null);

            echo json_encode(["ok" => $dao->crear($e)]);
            break;

        case "actualizar":
            $e = new Encargado();
            $e->setIdEncargado($_POST['idencargado']);
            $e->setNombres($_POST['nombres']);
            $e->setApellidos($_POST['apellidos']);
            $e->setTelefonoFijo($_POST['telefonofijo'] ?? null);
            $e->setTelefonoMovil($_POST['telefonomovil']);
            $e->setDni($_POST['dni'] ?? null);
            $e->setDireccion($_POST['direccion'] ?? null);
            $e->setCorreoElectronico($_POST['correoelectronico']);
            $e->setFechaNacimiento($_POST['fechanacimiento'] ?? null);
            $e->setSexo($_POST['sexo'] ?? null);

            echo json_encode(["ok" => $dao->actualizar($e)]);
            break;

        case "eliminar":
            echo json_encode(["ok" => $dao->eliminar($_POST['idencargado'])]);
            break;
    }
}
