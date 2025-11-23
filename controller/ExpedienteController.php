<?php
require_once __DIR__ . '/../model/ExpedienteDAO.php';
// Controlador para manejar la creación de expedientes
class ExpedienteController {
    public function store() {
        header('Content-Type: application/json');
        
        $mascota = $_POST['mascota_id'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $veterinario = $_POST['veterinario_id'] ?? '';

        if (empty($mascota) || empty($veterinario)) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos']);
            exit;
        }

        try {
            $dao = new ExpedienteDAO();
            $id = $dao->createExpediente($mascota, $fecha, $descripcion, $veterinario);
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

// Manejo de la solicitud para crear un expediente
if (isset($_GET['op']) && $_GET['op'] === 'store') {
    $controller = new ExpedienteController();
    $controller->store();
}
?>