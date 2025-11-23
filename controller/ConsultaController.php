<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/ExpedienteDAO.php';

class ConsultaController {
    
    private $dao;

    public function __construct() {
        $this->dao = new ExpedienteDAO();
    }

    public function mostrarIngresoConsulta() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?page=login");
            exit;
        }
        require_once __DIR__ . '/../view/consultas/ingresar_consulta.php';
    }

    public function buscarExpediente() {
        header('Content-Type: application/json');
        
        $busqueda = $_POST['busqueda'] ?? '';
        if (!$busqueda) {
            echo json_encode(['success' => false, 'message' => 'Campo vacío']);
            exit;
        }
        
        $resultado = $this->dao->buscarExpediente($busqueda);
        echo json_encode($resultado);
    }


    public function obtenerHistorial() {
        header('Content-Type: application/json');

        $idExpediente = $_POST['idexpediente'] ?? 0;
        $historial = $this->dao->obtenerHistorialMascota($idExpediente); 
        echo json_encode($historial);
    }

    public function guardarConsulta() {
        header('Content-Type: application/json');

        // Seguridad: Validar sesión
        if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Sesión expirada. Recarga la página.']);
            exit;
        }

        $datos = [
            'idexpediente' => $_POST['idexpediente'],
            'fecha'        => $_POST['fecha'],
            'peso'         => $_POST['peso'],
            'altura'       => $_POST['altura'],
            'resumen'      => $_POST['resumen'],
            'diagnostico'  => $_POST['diagnostico'],
            // ID tomado de la sesión
            'idusuario'    => $_SESSION['usuario']['id']
        ];

        $res = $this->dao->guardarConsulta($datos);
        echo json_encode($res);
    }
    
    public function agregarReceta() { /* Lógica futura */ }
    public function obtenerRecetas() { /* Lógica futura */ }
}
?>