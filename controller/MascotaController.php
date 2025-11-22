<?php
require_once "../model/MascotaDAO.php";

class MascotaController {
    public function listarJson() {
        header('Content-Type: application/json');
        try {
            $dao = new MascotaDAO();
            $datos = $dao->listarMascotas();
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
}

if (isset($_GET['op']) && $_GET['op'] === 'listar_json') {
    $c = new MascotaController();
    $c->listarJson();
}
?>
