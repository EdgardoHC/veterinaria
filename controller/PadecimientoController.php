<?php
require_once "../model/PadecimientoDAO.php";
$dao = new PadecimientoDAO();

header('Content-Type: application/json; charset=utf-8');

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case "listar":
            echo json_encode($dao->listar());
            break;

        case "crear":
            $p = new Padecimiento();
            $p->setNombre($_POST['nombre']);
            echo json_encode(["ok" => $dao->crear($p)]);
            break;

        case "actualizar":
            $p = new Padecimiento();
            $p->setIdPadecimiento($_POST['idPadecimiento']);
            $p->setNombre($_POST['nombre']);
            echo json_encode(["ok" => $dao->actualizar($p)]);
            break;

        case "eliminar":
            echo json_encode(["ok" => $dao->eliminar($_POST['idPadecimiento'])]);
            break;
    }
}
