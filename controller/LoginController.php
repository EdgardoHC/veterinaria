<?php
session_start();
require_once "../model/UsuarioDAO.php";

header("Content-Type: application/json; charset=utf-8");

$dao = new UsuarioDAO();

if (!isset($_POST['accion'])) {
    echo json_encode(["ok" => false, "msg" => "Acción no especificada"]);
    exit;
}

$accion = $_POST['accion'];

if ($accion === "login") {

    $usuario = $_POST['usuario'] ?? "";
    $pwd     = $_POST['pwd'] ?? "";

    if ($usuario === "" || $pwd === "") {
        echo json_encode(["ok" => false, "msg" => "Usuario y contraseña son obligatorios"]);
        exit;
    }

    // Buscar por correo o nombre de usuario
    $row = $dao->buscarPorEmailONombreUsuario($usuario);

    if ($row && $pwd === $row['contrasena'] && $row['estado'] == 1) {

        $_SESSION['usuario'] = [
            "id"         => $row['idusuario'],
            "nombre"     => $row['nombrecompleto'],
            "usuario"    => $row['nombreusuario'],
            "email"      => $row['correoelectronico'],
            "rol_nombre" => $row['nombre_rol']
        ];

        echo json_encode(["ok" => true]);
    } else {
        echo json_encode(["ok" => false, "msg" => "Usuario o contraseña incorrectos"]);
    }
    exit;
}

if ($accion === "logout") {
    session_destroy();
    echo json_encode(["ok" => true]);
    exit;
}

// Si llega otra cosa:
echo json_encode(["ok" => false, "msg" => "Acción no válida"]);
