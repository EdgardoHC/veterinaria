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
    "logout"    => "logout",
];

$page   = $_GET['page'] ?? "login";
$action = $_GET['action'] ?? "";

//    MANEJO DE ACCIONES
if (!empty($action)) {

    switch ($action) {

        case 'ingresar-consulta':
            require_once 'controller/ConsultaController.php';
            $controller = new ConsultaController();
            $controller->mostrarIngresoConsulta();
            exit;

        case 'buscarExpediente':
            require_once 'controller/ConsultaController.php';
            $controller = new ConsultaController();
            $controller->buscarExpediente();
            exit;

        case 'guardarConsulta':
            require_once 'controller/ConsultaController.php';
            $controller = new ConsultaController();
            $controller->guardarConsulta();
            exit;

        case 'agregarReceta':
            require_once 'controller/ConsultaController.php';
            $controller = new ConsultaController();
            $controller->agregarReceta();
            exit;

        case 'obtenerHistorial':
            require_once 'controller/ConsultaController.php';
            $controller = new ConsultaController();
            $controller->obtenerHistorial();
            exit;

        case 'obtenerRecetas':
            require_once 'controller/ConsultaController.php';
            $controller = new ConsultaController();
            $controller->obtenerRecetas();
            exit;

        case 'expediente-create':
            require_once 'controller/ExpedienteController.php';
            $controller = new ExpedienteController();
            $controller->create();
            exit;

        case 'expediente-store':
            require_once 'controller/ExpedienteController.php';
            $controller = new ExpedienteController();
            $controller->store();
            exit;

        case 'expediente':
            require_once 'controller/ExpedienteController.php';
            $controller = new ExpedienteController();
            exit;

        case 'mascota-listar-json':
            require_once 'controller/MascotaController.php';
            $c = new MascotaController();
            $c->listarJson();
            exit;

        case 'veterinario-listar-json':
            require_once 'controller/VeterinarioController.php';
            $c = new VeterinarioController();
            $c->listarJson();
            exit;

        case 'listarMascotas':
            require_once 'controller/MascotaController.php';
            $c = new MascotaController();
            $c->listarJson();
            exit;

        case 'listarVeterinarios':
            require_once 'controller/VeterinarioController.php';
            $c = new VeterinarioController();
            $c->listarJson();
            exit;

        default:
            break;
    }
}

// MANEJO DE PÁGINAS 
if (array_key_exists($page, $routes)) {

    if ($page === "logout") {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    // Proteger rutas privadas
    $rutasProtegidas = ["dashboard","home", "usuarios", "reporteUsuarios"];
    if (in_array($page, $rutasProtegidas) && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }
     if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] !== "Administrador") {
        // páginas restringidas solo para admin
        $soloAdmin = ["usuarios", "dashboard", "reporteUsuarios"];

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
