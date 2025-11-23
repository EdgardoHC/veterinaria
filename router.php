<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Definimos las rutas en un array
$routes = [
    "login"     => "view/login.php",
    "dashboard" => "view/dashboard.php",
    "home"      => "view/home.php",
    "usuarios"  => "view/vUsuario.php",
    "reporteUsuarios" => "reportes/reporteUsuarios.php",
    "dashboardReportes" => "view/dashboardReportes.php",
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
    $rutasProtegidas = ["dashboard","home", "usuarios", "reporteUsuarios", "dashboardReportes"];
    if (in_array($page, $rutasProtegidas) && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }
     if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] !== "Administrador") {
        // páginas restringidas solo para admin
        $soloAdmin = ["usuarios", "dashboard", "reporteUsuarios", "dashboardReportes"];

        if (in_array($page, $soloAdmin)) {
            // puedes redirigir al home o mostrar mensaje
            header("Location: index.php?page=home");
            exit;
        }
    }
    // Incluir la vista correspondiente
    require $routes[$page];

} else {
    http_response_code(404);
    require "view/errores/404.php";
}