<?php
require_once __DIR__ . "/../model/UsuarioDAO.php";
require_once __DIR__ . "/../model/Usuario.php";

header("Content-Type: application/json; charset=utf-8");

error_reporting(E_ALL);
ini_set('display_errors', 0); 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "message" => "Metodo no permitido"]);
    exit;
}

$accion = $_POST["accion"] ?? "";

if (isset($_GET['op']) && $_GET['op'] === 'listar_veterinarios_json') {
    $dao = new UsuarioDAO();
    echo json_encode($dao->listarVeterinarios());
    exit;
}

if ($accion === "") {
    http_response_code(400);
    echo json_encode(["ok" => false, "message" => "Accion requerida"]);
    exit;
}

$dao = new UsuarioDAO();

try {
    switch ($accion) {
        case "listar":
            $usuarios = $dao->listar();
            echo json_encode(["ok" => true, "data" => $usuarios]);
            break;

        case "crear":
            $nombre = trim($_POST["nombre"] ?? "");
            $apellidos = trim($_POST["apellidos"] ?? "");
            $email = strtolower(trim($_POST["email"] ?? ""));
            $apodo = trim($_POST["apodo"] ?? "");
            $pwd = $_POST["pwd"] ?? "";

            if ($nombre === "" || $email === "" || $apodo === "" || $pwd === "") {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Campos obligatorios vacíos"]);
                break;
            }

            $usuario = new Usuario();
            $usuario->setNombre($nombre);
            $usuario->setApellidos($apellidos);
            $usuario->setEmail($email);
            $usuario->setApodo($apodo);
            $usuario->setPwd($pwd);

            $resultado = $dao->crear($usuario);
            if ($resultado) {
                echo json_encode(["ok" => true, "message" => "Usuario creado"]);
            } else {
                http_response_code(500);
                echo json_encode(["ok" => false, "message" => "Error DB (Verificar logs)"]);
            }
            break;

        case "actualizar":
            $id = filter_var($_POST["idUsuario"] ?? null, FILTER_VALIDATE_INT);
            $nombre = trim($_POST["nombre"] ?? "");
            $apellidos = trim($_POST["apellidos"] ?? "");
            $email = strtolower(trim($_POST["email"] ?? ""));
            $apodo = trim($_POST["apodo"] ?? "");

            if (!$id) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "ID invalido"]);
                break;
            }

            $usuario = new Usuario();
            $usuario->setIdUsuario($id);
            $usuario->setNombre($nombre);
            $usuario->setApellidos($apellidos);
            $usuario->setEmail($email);
            $usuario->setApodo($apodo);

            $resultado = $dao->actualizar($usuario);
            if ($resultado) {
                echo json_encode(["ok" => true, "message" => "Usuario actualizado"]);
            } else {
                http_response_code(500);
                echo json_encode(["ok" => false, "message" => "No se pudo actualizar"]);
            }
            break;

        case "eliminar":
            $id = filter_var($_POST["idUsuario"] ?? null, FILTER_VALIDATE_INT);
            if (!$id) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "ID invalido"]);
                break;
            }

            $resultado = $dao->eliminar($id);
            if ($resultado) {
                echo json_encode(["ok" => true, "message" => "Usuario eliminado"]);
            } else {
                http_response_code(500);
                echo json_encode(["ok" => false, "message" => "No se pudo eliminar"]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["ok" => false, "message" => "Accion no soportada"]);
            break;
    }
} catch (Throwable $e) {
    error_log("Error Controller: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Error interno: " . $e->getMessage()]);
}
?>