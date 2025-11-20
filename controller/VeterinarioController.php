<?php
require_once "../model/UsuarioDAO.php";

class VeterinarioController
{
    public function listarJson()
    {
        header("Content-Type: application/json; charset=utf-8");

        $dao = new UsuarioDAO();

        try {
            $lista = $dao->listarVeterinarios(); 
            echo json_encode($lista, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
}
