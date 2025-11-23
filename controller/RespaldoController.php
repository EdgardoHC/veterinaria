<?php
require_once __DIR__ . "/../util/Seguridad.php";
require_once __DIR__ . "/../util/Respaldo.php";
require_once __DIR__ . "/../service/AuditoriaService.php";
require_once __DIR__ . "/../model/Auditoria.php";

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

$respaldo = new Respaldo();
$auditoriaService = new AuditoriaService();
$idUsuarioActual = $_SESSION['usuario']['id'];

try {
    switch ($accion) {
        case "crear":
            $comentario = Seguridad::sanitizarEntrada($_POST["comentario"] ?? '');
            
            $resultado = $respaldo->crearRespaldo($comentario);
            
            if ($resultado['exito']) {
                // Registrar en auditoría
                $auditoriaService->registrarAccion(
                    $idUsuarioActual,
                    Auditoria::ACCION_RESPALDO,
                    'base_datos',
                    null,
                    null,
                    ['archivo' => $resultado['archivo'], 'comentario' => $comentario]
                );
            }
            
            echo json_encode($resultado);
            break;

        case "listar":
            $respaldos = $respaldo->listarRespaldos();
            echo json_encode(["ok" => true, "data" => $respaldos]);
            break;

        case "restaurar":
            $nombreArchivo = Seguridad::sanitizarEntrada($_POST["archivo"] ?? '');
            
            if (empty($nombreArchivo)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Nombre de archivo requerido"]);
                break;
            }
            
            $resultado = $respaldo->restaurarRespaldo($nombreArchivo);
            
            if ($resultado['exito']) {
                // Registrar en auditoría
                $auditoriaService->registrarAccion(
                    $idUsuarioActual,
                    Auditoria::ACCION_RESTAURAR,
                    'base_datos',
                    null,
                    null,
                    ['archivo' => $nombreArchivo]
                );
            }
            
            echo json_encode($resultado);
            break;

        case "eliminar":
            $nombreArchivo = Seguridad::sanitizarEntrada($_POST["archivo"] ?? '');
            
            if (empty($nombreArchivo)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Nombre de archivo requerido"]);
                break;
            }
            
            $resultado = $respaldo->eliminarRespaldo($nombreArchivo);
            echo json_encode($resultado);
            break;

        default:
            http_response_code(400);
            echo json_encode(["ok" => false, "message" => "Accion no soportada"]);
            break;
    }
} catch (Throwable $e) {
    error_log("Error en RespaldoController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Error interno del servidor"]);
}

