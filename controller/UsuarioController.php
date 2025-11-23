<?php
require_once __DIR__ . "/../util/Seguridad.php";
require_once __DIR__ . "/../model/UsuarioDAO.php";
require_once __DIR__ . "/../model/Usuario.php";
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

$dao = new UsuarioDAO();
$auditoriaService = new AuditoriaService();
$idUsuarioActual = $_SESSION['usuario']['id'];

try {
    switch ($accion) {
        case "listar":
            $usuarios = $dao->listar();
            
            // Registrar consulta en auditoría
            $auditoriaService->registrarAccion(
                $idUsuarioActual,
                Auditoria::ACCION_CONSULTAR,
                'usuarios',
                null
            );
            
            echo json_encode(["ok" => true, "data" => $usuarios]);
            break;

        case "crear":
            $nombre = Seguridad::sanitizarEntrada(trim($_POST["nombre"] ?? ""));
            $apellidos = Seguridad::sanitizarEntrada(trim($_POST["apellidos"] ?? ""));
            $email = Seguridad::validarEmail($_POST["email"] ?? "");
            $apodo = Seguridad::sanitizarEntrada(trim($_POST["apodo"] ?? ""));
            $pwd = $_POST["pwd"] ?? "";

            if ($nombre === "" || $apellidos === "" || $email === false || $apodo === "" || $pwd === "") {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Todos los campos son obligatorios"]);
                break;
            }

            if (!Seguridad::validarPassword($pwd)) {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número"]);
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
                // Obtener el ID del usuario creado
                $usuarioCreado = $dao->buscarPorEmailOApodo($email);
                $idUsuarioCreado = $usuarioCreado['idUsuario'] ?? null;
                
                // Registrar en auditoría
                $auditoriaService->registrarAccion(
                    $idUsuarioActual,
                    Auditoria::ACCION_CREAR,
                    'usuarios',
                    $idUsuarioCreado,
                    null,
                    ['nombre' => $nombre, 'apellidos' => $apellidos, 'email' => $email, 'apodo' => $apodo]
                );
                
                echo json_encode(["ok" => true, "message" => "Usuario creado"]);
            } else {
                http_response_code(500);
                echo json_encode(["ok" => false, "message" => "No se pudo crear el usuario"]);
            }
            break;

        case "actualizar":
            $id = filter_var($_POST["idUsuario"] ?? null, FILTER_VALIDATE_INT);
            $nombre = Seguridad::sanitizarEntrada(trim($_POST["nombre"] ?? ""));
            $apellidos = Seguridad::sanitizarEntrada(trim($_POST["apellidos"] ?? ""));
            $email = Seguridad::validarEmail($_POST["email"] ?? "");
            $apodo = Seguridad::sanitizarEntrada(trim($_POST["apodo"] ?? ""));

            if (!$id || $nombre === "" || $apellidos === "" || $email === false || $apodo === "") {
                http_response_code(400);
                echo json_encode(["ok" => false, "message" => "Datos invalidos" ]);
                break;
            }

            // Obtener datos anteriores para auditoría
            $usuarioAnterior = $dao->buscarPorEmailOApodo($email);
            $datosAnteriores = null;
            if ($usuarioAnterior) {
                $datosAnteriores = [
                    'nombre' => $usuarioAnterior['nombre'],
                    'apellidos' => $usuarioAnterior['apellidos'],
                    'email' => $usuarioAnterior['email'],
                    'apodo' => $usuarioAnterior['apodo']
                ];
            }

            $usuario = new Usuario();
            $usuario->setIdUsuario($id);
            $usuario->setNombre($nombre);
            $usuario->setApellidos($apellidos);
            $usuario->setEmail($email);
            $usuario->setApodo($apodo);

            $resultado = $dao->actualizar($usuario);
            if ($resultado) {
                // Registrar en auditoría
                $auditoriaService->registrarAccion(
                    $idUsuarioActual,
                    Auditoria::ACCION_ACTUALIZAR,
                    'usuarios',
                    $id,
                    $datosAnteriores,
                    ['nombre' => $nombre, 'apellidos' => $apellidos, 'email' => $email, 'apodo' => $apodo]
                );
                
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

            // Obtener datos del usuario antes de eliminar para auditoría
            $usuarios = $dao->listar();
            $usuarioAEliminar = null;
            foreach ($usuarios as $u) {
                if ($u['idUsuario'] == $id) {
                    $usuarioAEliminar = $u;
                    break;
                }
            }

            $resultado = $dao->eliminar($id);
            if ($resultado) {
                // Registrar en auditoría
                $datosEliminados = null;
                if ($usuarioAEliminar) {
                    $datosEliminados = [
                        'nombre' => $usuarioAEliminar['nombre'],
                        'apellidos' => $usuarioAEliminar['apellidos'],
                        'email' => $usuarioAEliminar['email'],
                        'apodo' => $usuarioAEliminar['apodo']
                    ];
                }
                
                $auditoriaService->registrarAccion(
                    $idUsuarioActual,
                    Auditoria::ACCION_ELIMINAR,
                    'usuarios',
                    $id,
                    $datosEliminados,
                    null
                );
                
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
