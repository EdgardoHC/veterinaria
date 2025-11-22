<?php
require_once __DIR__ . '/../model/ExpedienteDAO.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Crear instancia del DAO
$dao = new ExpedienteDAO();

// Manejo de diferentes acciones relacionadas con consultas
switch ($action) {
    case 'buscar':
        $busqueda = $_POST['busqueda'] ?? '';
        if (!$busqueda) {
            echo json_encode(['success' => false, 'message' => 'Campo vacío']);
            exit;
        }
        
        $resultado = $dao->buscarExpediente($busqueda);
        echo json_encode($resultado);
        break;

    case 'historial':
        $idExpediente = $_POST['idexpediente'] ?? 0;
        $historial = $dao->obtenerHistorialMascota($idExpediente); 
        echo json_encode($historial);
        break;

    case 'guardar':
        $datos = [
            'idexpediente' => $_POST['idexpediente'],
            'fecha'        => $_POST['fecha'],
            'peso'         => $_POST['peso'],
            'altura'       => $_POST['altura'],
            'resumen'      => $_POST['resumen'],
            'diagnostico'  => $_POST['diagnostico'],
            'idusuario'    => $_POST['idusuario'] ?? 1 
        ];

        $res = $dao->guardarConsulta($datos);
        echo json_encode($res);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}
?>