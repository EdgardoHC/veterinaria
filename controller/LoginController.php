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

    $row = $dao->buscarPorEmailONombreUsuario($usuario);

    if (!$row || $row['estado'] != 1) {
        echo json_encode(["ok" => false, "msg" => "Usuario o contraseña incorrectos"]);
        exit;
    }

    $loginExitoso = false;

    if (password_verify($pwd, $row['contrasena'])) {
        $loginExitoso = true;
    } 
    else if ($pwd === $row['contrasena']) {
        $loginExitoso = true;
        
        try {
            $dao->actualizarContrasena($row['idusuario'], $pwd);
        } catch (Exception $e) {
  
            error_log("Error al migrar hash de usuario: " . $row['idusuario']);
        }
    }

    if ($loginExitoso) {

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

echo json_encode(["ok" => false, "msg" => "Acción no válida"]);