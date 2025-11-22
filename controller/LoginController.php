<?php
session_start();
require_once "../model/UsuarioDAO.php";

$dao = new UsuarioDAO();

if (isset($_POST['accion']) && $_POST['accion'] === "login") {
    $usuario = $_POST['usuario'];
    $pwd = $_POST['pwd'];

    $row = $dao->buscarPorEmailOApodo($usuario);

    if ($row && password_verify($pwd, $row['pwd'])) {
        $_SESSION['usuario'] = [
            "id" => $row['idUsuario'],
            "nombre" => $row['nombre'],
            "apellidos" => $row['apellidos'],
            "email" => $row['email'],
            "apodo" => $row['apodo']
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
