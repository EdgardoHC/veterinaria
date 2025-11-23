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
           $nombre     = trim($_POST["nombre"] ?? "");
           $email      = strtolower(trim($_POST["email"] ?? ""));
           $username   = trim($_POST["username"] ?? "");
           $pwd        = $_POST["pwd"] ?? "";
           $rol        = trim($_POST["rol"] ?? "");
           $idempleado = filter_var($_POST["idempleado"] ?? null, FILTER_VALIDATE_INT);

           $estado = 1; // activo por defecto

            if ($nombre === "" || $email === "" || $username === "" || $pwd === "" || $rol === "" || !$idempleado) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Todos los campos son obligatorios"]);
                break;
            }


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Campos obligatorios vacíos"]);
                break;
            }

             $usuario = new Usuario();
             $usuario->setNombre($nombre);
             $usuario->setEmail($email);
             $usuario->setNombreUsuario($username);
             $usuario->setPwd($pwd);
             $usuario->setIdRol((int)$rol);
             $usuario->setEstado((int)$estado);    // siempre 1
             $usuario->setIdEmpleado((int)$idempleado);

             $resultado = $dao->crear($usuario);
             if ($resultado) {
                 echo json_encode(["ok" => true, "message" => "Usuario creado"]);
             } else {
                 http_response_code(500);
                 echo json_encode(["ok" => false, "message" => "No se pudo crear el usuario"]);
             }
            break;

        case "actualizar":
            $id       = filter_var($_POST["idUsuario"] ?? null, FILTER_VALIDATE_INT);
            $nombre   = trim($_POST["nombre"] ?? "");
            $username = trim($_POST["username"] ?? "");
            $email    = strtolower(trim($_POST["email"] ?? ""));
            $rol      = trim($_POST["rol"] ?? "");
            $estado   = trim($_POST["estado"] ?? "");
            $pwd      = $_POST["pwd"] ?? ""; 

            // --- LÍNEA CORREGIDA (sin el ')' extra) ---
            if (!$id || $nombre === "" || $username === "" || $email === "" || $rol === "" || $estado === "") {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Datos invalidos"]);
                break;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "ID invalido"]);
                break;
            }

            $usuario = new Usuario();
            $usuario->setIdUsuario($id);
            $usuario->setNombre($nombre);
            // apellidos por ahora no lo tocamos
            //$usuario->setApellidos("");                
            $usuario->setNombreUsuario($username);  // apodo
            $usuario->setEmail($email);
            $usuario->setEstado((int)$estado);
            $usuario->setIdRol((int)$rol);
            $usuario->setPwd($pwd);

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
    // AHORA devolvemos el mensaje real para que lo veas en el SweetAlert
    error_log("Error en UsuarioController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}
