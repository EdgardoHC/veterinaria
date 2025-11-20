<?php
require_once __DIR__ . "/../util/Seguridad.php";
require_once __DIR__ . "/../service/AuditoriaService.php";

header("Content-Type: application/json; charset=utf-8");

// Iniciar sesión segura
Seguridad::iniciarSesionSegura();

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "message" => "No autorizado"]);
    exit;
}

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

$auditoriaService = new AuditoriaService();

try {
    switch ($accion) {
        case "listar":
            $filtros = [];
            
            if (!empty($_POST["idUsuario"])) {
                $filtros['idUsuario'] = filter_var($_POST["idUsuario"], FILTER_VALIDATE_INT);
            }
            
            if (!empty($_POST["accion"])) {
                $filtros['accion'] = Seguridad::sanitizarEntrada($_POST["accion"]);
            }
            
            if (!empty($_POST["tabla"])) {
                $filtros['tabla'] = Seguridad::sanitizarEntrada($_POST["tabla"]);
            }
            
            if (!empty($_POST["fechaDesde"])) {
                $filtros['fechaDesde'] = $_POST["fechaDesde"];
            }
            
            if (!empty($_POST["fechaHasta"])) {
                $filtros['fechaHasta'] = $_POST["fechaHasta"];
            }
            
            $registros = $auditoriaService->listar($filtros);
            echo json_encode(["ok" => true, "data" => $registros]);
            break;

        case "estadisticas":
            $fechaDesde = $_POST["fechaDesde"] ?? null;
            $fechaHasta = $_POST["fechaHasta"] ?? null;
            
            $estadisticas = $auditoriaService->obtenerEstadisticas($fechaDesde, $fechaHasta);
            echo json_encode(["ok" => true, "data" => $estadisticas]);
            break;

        default:
            http_response_code(400);
            echo json_encode(["ok" => false, "message" => "Accion no soportada"]);
            break;
    }
} catch (Throwable $e) {
    error_log("Error en AuditoriaController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Error interno del servidor"]);
}

