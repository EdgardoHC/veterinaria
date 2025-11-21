<?php
// Definimos las rutas en un array
$routes = [
    "login"     => "view/login.php",
    "dashboard" => "view/dashboard.php",
    "usuarios"  => "view/vUsuario.php",
    "reporteUsuarios" => "reportes/reporteUsuarios.php",
    "logout"    => "logout",
];

// Pagina pedida
$page = $_GET['page'] ?? "login";

// Verificamos si existe la ruta
if (array_key_exists($page, $routes)) {

    // Si es logout
    if ($page === "logout") {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    // Proteger rutas privadas
    $rutasProtegidas = ["dashboard", "usuarios", "reporteUsuarios"];
    if (in_array($page, $rutasProtegidas) && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }

    // Incluir la vista correspondiente
    require $routes[$page];

} else {
    http_response_code(404);
    require "view/errores/404.php";
}
