<?php
require_once "../model/VacunaDAO.php";

$dao = new VacunaDAO();

header('Content-Type: application/json; charset=utf-8');

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case "listar":
            // Devuelve todas las vacunas
            echo json_encode($dao->listar());
            break;

        case "crear":
            // Crear nueva vacuna
            $v = new Vacuna();
            $v->setNombre($_POST['nombre']);

            echo json_encode(["ok" => $dao->crear($v)]);
            break;

        case "actualizar":
            // Actualizar vacuna existente
            $v = new Vacuna();
            $v->setIdVacuna($_POST['idvacuna']);   // viene del input hidden
            $v->setNombre($_POST['nombre']);

            echo json_encode(["ok" => $dao->actualizar($v)]);
            break;

        case "eliminar":
            // Eliminar vacuna
            echo json_encode(["ok" => $dao->eliminar($_POST['idvacuna'])]);
            break;
    }
}
