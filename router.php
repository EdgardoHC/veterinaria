<?php
// Definimos las rutas en un array
//para el equipo 2: se iniciará con la pantalla de home.
$routes = [
    "login"     => "view/login.php",
    "home" => "view/home.php",
    "raza" => "view/raza.php", // para el equipo 2: declaren las rutas de sus catalogos en el arreglo.
    "vacunas" => "view/vacunas.php",
    "Encargado" => "view/encargado.php",
    "padecimientos" => "view/padecimientos.php",
    "dashboard" => "view/dashboard.php",
    "usuarios"  => "view/vUsuario.php",
    "reporteUsuarios" => "reportes/reporteUsuarios.php",
    "logout"    => "logout",
];

// Pagina pedida
$page = $_GET['page'] ?? "home";

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
