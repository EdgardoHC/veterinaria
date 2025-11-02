<?php
session_start();
require_once "../model/UsuarioDAO.php";

$dao = new UsuarioDAO();

if (isset($_POST['accion']) && $_POST['accion'] === "login") {
    $usuario = $_POST['usuario'];
    $pwd = $_POST['pwd'];

    $row = $dao->buscarPorEmailOApodo($usuario);

    //if ($row && password_verify($pwd, $row['pwd'])) {
    if ($row && $pwd === $row['contrasena']) {
        // Guardamos datos en la sesión
        $_SESSION['usuario'] = [
            "id" => $row['idusuario'],
            "nombre" => $row['nombrecompleto'],
            "usuario" => $row['nombreusuario'],
            "email" => $row['correoelectronico'],
            "rol_nombre" => $row['nombre_rol']
        ];

        echo json_encode(["ok" => true]);
    } else {
        echo json_encode(["ok" => false, "msg" => "Usuario o contraseña incorrectos"]);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === "logout") {
    session_destroy();
    echo json_encode(["ok" => true]);
}
