<?php
require_once "../model/UsuarioDAO.php";
require_once "../model/Usuario.php";

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

            if ($nombre === "" || $apellidos === "" || $email === "" || $apodo === "" || $pwd === "") {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Todos los campos son obligatorios"]);
                break;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Email invalido"]);
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
                echo json_encode(["ok" => false, "message" => "No se pudo crear el usuario"]);
            }
            break;

        case "actualizar":
            $id = filter_var($_POST["idUsuario"] ?? null, FILTER_VALIDATE_INT);
            $nombre = trim($_POST["nombre"] ?? "");
            $apellidos = trim($_POST["apellidos"] ?? "");
            $email = strtolower(trim($_POST["email"] ?? ""));
            $apodo = trim($_POST["apodo"] ?? "");

            if (!$id || $nombre === "" || $apellidos === "" || $email === "" || $apodo === "") {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Datos invalidos" ]);
                break;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Email invalido"]);
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
                echo json_encode(["ok" => false, "message" => "No se pudo actualizar el usuario"]);
            }
            break;

        case "eliminar":
            $id = filter_var($_POST["idUsuario"] ?? null, FILTER_VALIDATE_INT);
            if (!$id) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Identificador invalido"]);
                break;
            }

            $resultado = $dao->eliminar($id);
            if ($resultado) {
                echo json_encode(["ok" => true, "message" => "Usuario eliminado"]);
            } else {
                http_response_code(500);
                echo json_encode(["ok" => false, "message" => "No se pudo eliminar el usuario"]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["ok" => false, "message" => "Accion no soportada"]);
            break;
    }
} catch (Throwable $e) {
    error_log("Error en UsuarioController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Error interno del servidor"]);
}
