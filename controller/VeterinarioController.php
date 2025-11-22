<?php
require_once __DIR__ . '/../model/UsuarioDAO.php';

class UsuarioController {
    public function listarVeterinariosJson() {
        header('Content-Type: application/json');
        try {
            $dao = new UsuarioDAO();
            $datos = $dao->listarVeterinarios(); 
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
}

if (isset($_GET['op']) && $_GET['op'] === 'listar_veterinarios_json') {
    $c = new UsuarioController();
    $c->listarVeterinariosJson();
}
?>