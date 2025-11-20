<?php
require_once __DIR__ . '/../model/ExpedienteDAO.php';
require_once __DIR__ . '/../model/MascotaDAO.php';
require_once __DIR__ . '/../model/UsuarioDAO.php';

class ConsultaController {
    private $expedienteDAO;
    private $mascotaDAO;
    private $recetaDAO;
    
    public function __construct() {
        $this->expedienteDAO = new ExpedienteDAO();
        $this->mascotaDAO = new MascotaDAO();
        $this->recetaDAO = new RecetaDAO();
    }
    
    public function mostrarIngresoConsulta() {
        require_once '../view/consultas/ingresar_consulta.php';
    }
    
    public function buscarExpediente() {
        $busqueda = $_POST['busqueda'] ?? '';
        $resultado = $this->expedienteDAO->buscarExpediente($busqueda);
        echo json_encode($resultado);
    }
    
    public function guardarConsulta() {
        $datos = [
            'fecha' => $_POST['fecha'],
            'resumen' => $_POST['resumen'],
            'diagnostico' => $_POST['diagnostico'],
            'idexpediente' => $_POST['idexpediente'],
            'peso' => $_POST['peso'],
            'altura' => $_POST['altura'],
            'idusuario' => 1 
        ];
        $resultado = $this->expedienteDAO->guardarConsulta($datos);
        echo json_encode($resultado);
    }
    
    public function agregarReceta() {
        $datos = [
            'fecha' => $_POST['fecha'],
            'descripcion' => $_POST['descripcion'],
            'idexpedientedetalle' => $_POST['idexpedientedetalle'],
            'dosis' => $_POST['dosis']
        ];
        $resultado = $this->recetaDAO->guardarReceta($datos);
        echo json_encode($resultado);
    }
    
    public function obtenerHistorial() {
        $idMascota = $_POST['idmascota'] ?? '';
        $historial = $this->expedienteDAO->obtenerHistorialMascota($idMascota);
        echo json_encode($historial);
    }
    
    public function obtenerRecetas() {
        $idexpedientedetalle = $_POST['idexpedientedetalle'] ?? '';
        $recetas = $this->recetaDAO->obtenerRecetasPorExpediente($idexpedientedetalle);
        echo json_encode($recetas);
    }
}
?>