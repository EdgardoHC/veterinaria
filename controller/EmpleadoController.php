<?php
require_once "../model/EmpleadoDAO.php";

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "message" => "Metodo no permitido"]);
    exit;
}

$accion = $_POST["accion"] ?? "";
if ($accion === "") {
    http_response_code(400);
    echo json_encode(["ok" => false, "message" => "Accion requerida"]);
    exit;
}

$dao = new EmpleadoDAO();

try {
    switch ($accion) {

        // Para NUEVO usuario (solo empleados libres)
        case "listarLibres":
            $empleados = $dao->listarEmpleadosSinUsuario();
            echo json_encode(["ok" => true, "data" => $empleados]);
            break;

        // Para EDITAR usuario (libres + el empleado ya asignado a ese idusuario)
        case "listarParaEdicion":
            $idusuario = isset($_POST["idusuario"]) ? (int)$_POST["idusuario"] : 0;
            if ($idusuario <= 0) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "idusuario requerido"]);
                exit;
            }
            $empleados = $dao->listarEmpleadosParaEdicion($idusuario);
            echo json_encode(["ok" => true, "data" => $empleados]);
            break;

        default:
            http_response_code(400);
            echo json_encode(["ok" => false, "message" => "Accion no soportada"]);
            break;
    }
} catch (Throwable $e) {
    error_log("Error en EmpleadoController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}
