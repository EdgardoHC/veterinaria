<?php
require_once "../model/MascotaDAO.php";

class MascotaController
{
    public function listarJson()
    {
        header("Content-Type: application/json; charset=utf-8");

        $dao = new MascotaDAO();

        try {
            $lista = $dao->listarMascotas();
            echo json_encode($lista, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
}
