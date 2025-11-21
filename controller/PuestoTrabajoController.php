<?php
/**
 * Controlador para Puesto de Trabajo
 */

require_once '../model/PuestoTrabajoDAO.php';
require_once '../model/PuestoTrabajo.php';

header('Content-Type: application/json');

$dao = new PuestoTrabajoDAO();

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'listar':
            $lista = $dao->listar();
            echo json_encode($lista);
            break;

        case 'guardar':
            $nombre = trim($_POST['nombre'] ?? '');
            $idArea = $_POST['idAreaTrabajo'] ?? 0; // Recibimos el ID del select

            if (empty($nombre) || empty($idArea)) {
                echo json_encode(['estatus' => false, 'mensaje' => 'Faltan datos obligatorios']);
                exit;
            }

            $puesto = new PuestoTrabajo();
            $puesto->setNombre($nombre);
            $puesto->setIdAreaTrabajo($idArea);

            if ($dao->guardar($puesto)) {
                echo json_encode(['estatus' => true, 'mensaje' => 'Puesto guardado correctamente']);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'Error al guardar']);
            }
            break;

        case 'obtener':
            $id = $_POST['idPuestoTrabajo'] ?? 0;
            $puesto = $dao->obtener($id);

            if ($puesto) {
                echo json_encode(['estatus' => true, 'data' => $puesto]);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'Puesto no encontrado']);
            }
            break;

        case 'actualizar':
            $id = $_POST['idPuestoTrabajo'] ?? 0;
            $nombre = trim($_POST['nombre'] ?? '');
            $idArea = $_POST['idAreaTrabajo'] ?? 0;

            if (empty($id) || empty($nombre) || empty($idArea)) {
                echo json_encode(['estatus' => false, 'mensaje' => 'Faltan datos para actualizar']);
                exit;
            }

            $puesto = new PuestoTrabajo();
            $puesto->setIdPuestoTrabajo($id);
            $puesto->setNombre($nombre);
            $puesto->setIdAreaTrabajo($idArea);

            if ($dao->actualizar($puesto)) {
                echo json_encode(['estatus' => true, 'mensaje' => 'Puesto actualizado correctamente']);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'Error al actualizar']);
            }
            break;

        case 'eliminar':
            $id = $_POST['idPuestoTrabajo'] ?? 0;
            if ($dao->eliminar($id)) {
                echo json_encode(['estatus' => true, 'mensaje' => 'Puesto eliminado correctamente']);
            } else {
                echo json_encode(['estatus' => false, 'mensaje' => 'No se puede eliminar este puesto']);
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