<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Controladores principales
require_once 'controller/ConsultaController.php';
require_once 'controller/ExpedienteController.php';

// Definimos las rutas en un array
$routes = [
    "login"     => "view/login.php",
    "dashboard" => "view/dashboard.php",
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

        case 'expediente-create':
            $controller = new ExpedienteController();
            $controller->create();
            exit;

        case 'expediente-store':
            $controller = new ExpedienteController();
            $controller->store();
            exit;

        case 'expediente':
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

    $rutasProtegidas = ["dashboard", "usuarios", "reporteUsuarios"];
    if (in_array($page, $rutasProtegidas) && !isset($_SESSION['usuario'])) {
        header("Location: index.php?page=login");
        exit;
    }

    require $routes[$page];

} else {
    http_response_code(404);
    require "view/errores/404.php";
}
