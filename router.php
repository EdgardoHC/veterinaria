<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agregar controladores necesarios (De Procesos)
// Mantenemos la inclusión del controlador. Si el archivo falta, 
// el bloque de acciones está comentado, por lo que no causará error.
require_once 'controller/ConsultaController.php';

// Definimos las rutas en un array
// Unificamos las declaraciones de rutas
$routes = [
    "login"           => "view/login.php",
    "dashboard"       => "view/dashboard.php",
    "home"            => "view/home.php",
    "usuarios"        => "view/vUsuario.php",
    "reporteUsuarios" => "reportes/reporteUsuarios.php",
    
    // RUTAS DE CATÁLOGO (Integradas de Updated upstream y Stash)
    "raza"            => "view/raza.php",
    "padecimientos"   => "view/padecimientos.php",
    "razas"           => "view/razas.php", // De la versión Stashed
    
    // RUTAS DE SEGURIDAD
    "auditoria"       => "view/vAuditoria.php", 
    "respaldo"        => "view/vRespaldo.php",  
    "logout"          => "logout",
];

// Pagina pedida
// Lógica de navegación combinada: si está logueado, va a 'home'; si no, va a 'login'.
$defaultPage = isset($_SESSION['usuario']) ? "home" : "login";
$page = $_GET['page'] ?? $defaultPage;
$action = $_GET['action'] ?? '';

// Manejar acciones primero (CÓDIGO DEL EQUIPO 4)
// Mantenemos el bloque comentado hasta que el compañero suba los archivos DAO faltantes.
/*
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
*/

// Verificamos si existe la ruta de página
if (array_key_exists($page, $routes)) {

    // Si es logout
    if ($page === "logout") {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    // Proteger rutas privadas
    $rutasProtegidas = array_keys($routes);
    
    if (in_array($page, $rutasProtegidas) && $page !== 'login' && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }

    // Restricciones de Rol (Admin)
    if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] !== "Administrador") {
        // Se incluyen las rutas de catálogo que solo el admin debe gestionar.
        $soloAdmin = ["usuarios", "dashboard", "reporteUsuarios", "auditoria", "respaldo", "raza", "padecimientos", "razas"];

        if (in_array($page, $soloAdmin)) {
            // redirigir al home
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