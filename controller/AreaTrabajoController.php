<?php
// Incluimos los modelos necesarios
require_once '../model/AreaTrabajoDAO.php';
require_once '../model/AreaTrabajo.php';

// Configuramos la respuesta para que sea siempre JSON
header('Content-Type: application/json');

$dao = new AreaTrabajoDAO();

// Validamos si se recibió una acción por POST
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'listar':
            $lista = $dao->listar();
            echo json_encode($lista);
            break;

        case 'guardar':
            // Recibimos el nombre
            $nombre = trim($_POST['nombre'] ?? '');

            if (empty($nombre)) {
                echo json_encode(['estatus' => false, 'mensaje' => 'El nombre es obligatorio']);
                exit;
            }

            $area = new AreaTrabajo();
            $area->setNombre($nombre);

            if ($dao->guardar($area)) {
                echo json_encode(['estatus' => true, 'mensaje' => 'Área registrada correctamente']);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'Error al registrar en BD']);
            }
            break;

        case 'obtener':
            $id = $_POST['idAreaTrabajo'] ?? 0;
            $area = $dao->obtener($id);
            if ($area) {
                echo json_encode(['estatus' => true, 'data' => $area]);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'No encontrado']);
            }
            break;

        case 'actualizar':
            $id = $_POST['idAreaTrabajo'] ?? 0;
            $nombre = trim($_POST['nombre'] ?? '');

            if (empty($id) || empty($nombre)) {
                echo json_encode(['estatus' => false, 'mensaje' => 'Datos incompletos']);
                exit;
            }

            $area = new AreaTrabajo();
            $area->setIdAreaTrabajo($id);
            $area->setNombre($nombre);

            if ($dao->actualizar($area)) {
                echo json_encode(['estatus' => true, 'mensaje' => 'Área actualizada']);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'Error al actualizar']);
            }
            break;

        case 'eliminar':
            $id = $_POST['idAreaTrabajo'] ?? 0;
            if ($dao->eliminar($id)) {
                echo json_encode(['estatus' => true, 'mensaje' => 'Área eliminada']);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'No se puede eliminar, está en uso o no existe']);
            }
            break;

        default:
            echo json_encode(['estatus' => false, 'mensaje' => 'Acción no válida']);
            break;
    }
} else {
    echo json_encode(['estatus' => false, 'mensaje' => 'Acceso denegado']);
}
?>