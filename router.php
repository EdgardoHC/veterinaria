<?php
// Iniciar sesión si no está iniciada (TU CÓDIGO)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agregar controlador necesario (CÓDIGO DEL EQUIPO 4)
require_once 'controller/ConsultaController.php';

// Definimos las rutas en un array
$routes = [
    "login"           => "view/login.php",
    "dashboard"       => "view/dashboard.php",
    "home"            => "view/home.php",
    "usuarios"        => "view/vUsuario.php",
    "reporteUsuarios" => "reportes/reporteUsuarios.php",
    // RUTAS AÑADIDAS POR SEGURIDAD/HERRAMIENTAS (CÓDIGO INTEGRADO)
    "auditoria"       => "view/vAuditoria.php", 
    "respaldo"        => "view/vRespaldo.php",  
    "logout"          => "logout",
];

// Pagina pedida
$page = $_GET['page'] ?? "login";
$action = $_GET['action'] ?? '';

// Manejar acciones primero (CÓDIGO DEL EQUIPO 4)
if (!empty($action)) {
    switch($action) {
        case 'ingresar-consulta':
            $controller = new ConsultaController();
            $controller->mostrarIngresoConsulta();
            exit;
            
        case 'buscarExpediente':
            $controller = new ConsultaController();
            $controller->buscarExpediente();
            exit;
            
        case 'guardarConsulta':
            $controller = new ConsultaController();
            $controller->guardarConsulta();
            exit;
            
        case 'agregarReceta':
            $controller = new ConsultaController();
            $controller->agregarReceta();
            exit;
            
        case 'obtenerHistorial':
            $controller = new ConsultaController();
            $controller->obtenerHistorial();
            exit;
            
        case 'obtenerRecetas':
            $controller = new ConsultaController();
            $controller->obtenerRecetas();
            exit;
            
        default:
            // Si la acción no existe, continuamos con el flujo normal de páginas
            break;
    }
}

// Verificamos si existe la ruta de página
if (array_key_exists($page, $routes)) {

    // Si es logout
    if ($page === "logout") {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    // Proteger rutas privadas (LISTA INTEGRADA)
    $rutasProtegidas = ["dashboard", "home", "usuarios", "reporteUsuarios", "auditoria", "respaldo"];
    if (in_array($page, $rutasProtegidas) && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }
     if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] !== "Administrador") {
        // páginas restringidas solo para admin
        // Nota: 'home' se elimina de la restricción para que los usuarios normales puedan acceder a ella.
        $soloAdmin = ["usuarios", "dashboard", "reporteUsuarios", "auditoria", "respaldo"];

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
    // Verificar si existe el directorio de errores
    $errorFile = "view/errores/404.php";
    if (file_exists($errorFile)) {
        require $errorFile;
    } else {
        require "404.php";
    }
}
?>