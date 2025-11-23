<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/ExpedienteDAO.php';

class ExpedienteController {
    
    public function create() {

        if (!isset($_SESSION['usuario'])) {
            header("Location: ../index.php?page=login");
            exit;
        }
        require_once __DIR__ . '/../view/expediente/create.php'; 
    }

    public function store() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Sesión expirada o no válida. Recarga la página.']);
            exit;
        }

        $mascota = $_POST['mascota_id'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        $veterinario = $_SESSION['usuario']['id'];

        if (empty($mascota) || empty($fecha) || empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
            exit;
        }

        try {
            $dao = new ExpedienteDAO();
            $id = $dao->createExpediente($mascota, $fecha, $descripcion, $veterinario);
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
        }
    }
}

// Manejo de la solicitud directa (AJAX)
if (isset($_GET['op']) && $_GET['op'] === 'store') {
    $controller = new ExpedienteController();
    $controller->store();
}
?>