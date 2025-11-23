<?php
require_once "../model/RazaDAO.php";
$dao = new RazaDAO();

header('Content-Type: application/json; charset=utf-8');

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case "listar":
            echo json_encode($dao->listar());
            break;
        case "crear":
            $r = new Raza();
            $r->setNombre($_POST['nombre']);

            echo json_encode(["ok" => $dao->crear($r)]);
            break;

        case "actualizar":
            $r = new Raza();
            $r->setIdRaza($_POST['idRaza']);
            $r->setNombre($_POST['nombre']);
            echo json_encode(["ok" => $dao->actualizar($r)]);
            break;

        case "eliminar":
            echo json_encode(["ok" => $dao->eliminar($_POST['idRaza'])]);
            break;
    }
}
