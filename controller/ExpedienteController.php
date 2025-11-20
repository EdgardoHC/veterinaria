<?php
require_once __DIR__ . '/../model/Expediente.php';
require_once __DIR__ . '/../model/ExpedienteDAO.php';
require_once __DIR__ . '/../model/MascotaDAO.php';
require_once __DIR__ . '/../model/UsuarioDAO.php';

class ExpedienteController {

    public function create() {
        require_once __DIR__ . '/../view/expediente/create.php';
    }

    public function store() {
        header('Content-Type: application/json');

        $mascota = $_POST['mascota_id'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;
        $veterinario = $_POST['veterinario_id'] ?? null;

        if (!$mascota || !$fecha || !$descripcion || !$veterinario) {
            echo json_encode(['success' => false, 'message' => 'Falta información']);
            return;
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
