<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agregar controladores necesarios (De Procesos)
require_once 'controller/ConsultaController.php';
// Nota: Si el LoginController.php usa Seguridad::iniciarSesionSegura(), 
// este archivo router.php ya no necesita el session_start(). 
// Pero lo mantenemos por compatibilidad.

// Definimos las rutas en un array
// Unificamos las declaraciones de rutas
$routes = [
    "login"           => "view/login.php",
    "dashboard"       => "view/dashboard.php",
    "home"            => "view/home.php",
    "usuarios"        => "view/vUsuario.php",
    "reporteUsuarios" => "reportes/reporteUsuarios.php",
    // Rutas de Catálogo (Equipo 2)
    "raza"            => "view/raza.php",
    "padecimientos"   => "view/padecimientos.php",
    // Rutas de Seguridad (Integradas)
    "auditoria"       => "view/vAuditoria.php", 
    "respaldo"        => "view/vRespaldo.php",  
    "logout"          => "logout",
];

// Pagina pedida
// CAMBIO CLAVE: Usamos 'home' como página por defecto si el usuario está logueado,
// pero si no lo está, debe ir a 'login'.
$defaultPage = isset($_SESSION['usuario']) ? "home" : "login";
$page = $_GET['page'] ?? $defaultPage;
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
    // El usuario NO logueado solo puede ver 'login'.
    $rutasProtegidas = array_keys($routes); // Todas las rutas excepto login/logout son protegidas
    
    if (in_array($page, $rutasProtegidas) && $page !== 'login' && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }

    // Restricciones de Rol (Admin)
     if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] !== "Administrador") {
        // páginas restringidas solo para admin
        // Se incluyen las rutas de catálogo que solo el admin debe gestionar.
        $soloAdmin = ["usuarios", "dashboard", "reporteUsuarios", "auditoria", "respaldo", "raza", "padecimientos"];

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