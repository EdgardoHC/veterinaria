<?php
date_default_timezone_set('America/El_Salvador');
 
require_once __DIR__ . "/../model/recetaModel.php";
header('Content-Type: application/json; charset=utf-8');
 
$model = new RecetaModel();
 
$action = $_GET['action'] ?? '';
 
switch ($action) {
 
    case 'listarExpedientes':
        echo json_encode($model->obtenerExpedientes());
        break;
 
    case 'listar':
        echo json_encode($model->obtenerRecetas());
        break;
 
    case 'agregar':
        $fecha        = $_POST['fecha'] ?? null;
        $descripcion  = $_POST['descripcion'] ?? null;
        $idexpediente = $_POST['idexpedientedetalle'] ?? null;
        $dosis        = $_POST['dosis'] ?? null;
 
        if (!$fecha || !$descripcion || !$idexpediente || !$dosis) {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
            break;
        }
 
        $ok = $model->agregarReceta($fecha, $descripcion, $idexpediente, $dosis);
        echo json_encode(['success' => (bool)$ok]);
        break;
 
        
    case 'editar':
        $id           = $_POST['idreceta'] ?? null;
        $fecha        = $_POST['fecha'] ?? null;
        $descripcion  = $_POST['descripcion'] ?? null;
        $idexpediente = $_POST['idexpedientedetalle'] ?? null;
        $dosis        = $_POST['dosis'] ?? null;
 
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            break;
        }
 
        $ok = $model->editarReceta($id, $fecha, $descripcion, $idexpediente, $dosis);
        echo json_encode(['success' => (bool)$ok]);
        break;
 
    case 'eliminar':
        $id = $_POST['id'] ?? null;
 
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            break;
        }
 
        $ok = $model->eliminarReceta($id);
        echo json_encode(['success' => (bool)$ok]);
        break;
 
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
 