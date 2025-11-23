<?php
date_default_timezone_set('America/El_Salvador'); // Ajusta la zona horaria
require_once __DIR__ . "/../model/cartillaModel.php";
header('Content-Type: application/json; charset=utf-8');
 
$model = new CartillaModel();
 
$action = isset($_GET['action']) ? $_GET['action'] : '';
 
switch ($action) {
 
    case 'listarMascotas':
        echo json_encode($model->obtenerMascotas());
        break;
 
    case 'listarUsuarios':
        echo json_encode($model->obtenerUsuarios());
        break;
 
    case 'listar':
        echo json_encode($model->obtenerCartillas());
        break;
 
    case 'agregar':
        $idmascota = $_POST['idmascota'] ?? null;
        $fecha     = $_POST['fecha'] ?? null;
        $peso      = $_POST['peso'] ?? null;
        $altura    = $_POST['altura'] ?? null;
        $idusuario = $_POST['idusuario'] ?? null;
 
        if (!$idmascota || !$fecha || $peso === null || $altura === null || !$idusuario) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos']);
            break;
        }
 
        // Agregar hora, minutos y segundos actuales a la fecha
        $fecha .= ' ' . date('H:i:s');
 
        $ok = $model->agregarCartilla($idmascota, $fecha, $peso, $altura, $idusuario);
        echo json_encode(['success' => (bool)$ok]);
        break;
 
    case 'editar':
        $id         = $_POST['idcartillavacunacion'] ?? null;
        $idmascota  = $_POST['idmascota'] ?? null;
        $fecha      = $_POST['fecha'] ?? null;
        $peso       = $_POST['peso'] ?? null;
        $altura     = $_POST['altura'] ?? null;
        $idusuario  = $_POST['idusuario'] ?? null;
 
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido para editar']);
            break;
        }
 
        // Agregar hora, minutos y segundos actuales a la fecha
        if ($fecha) {
            $fecha .= ' ' . date('H:i:s');
        }
 
        $ok = $model->editarCartilla($id, $idmascota, $fecha, $peso, $altura, $idusuario);
        echo json_encode(['success' => (bool)$ok]);
        break;
 
    case 'eliminar':
        $id = $_POST['id'] ?? null;
 
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            break;
        }
 
        $ok = $model->eliminarCartilla($id);
        echo json_encode(['success' => (bool)$ok]);
        break;
 
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}