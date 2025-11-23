<?php
require_once __DIR__ . "/../model/AuditoriaDAO.php";
require_once __DIR__ . "/../model/Auditoria.php";

/**
 * Servicio de Auditoría
 * Grupo 6: Seguridad, auditoría y respaldo de datos
 */
class AuditoriaService
{
    private $dao;

    public function __construct()
    {
        $this->dao = new AuditoriaDAO();
    }

    /**
     * Registra una acción en la auditoría
     */
    public function registrarAccion(
        $idUsuario,
        $accion,
        $tabla = null,
        $idRegistro = null,
        $datosAnteriores = null,
        $datosNuevos = null
    ) {
        $auditoria = new Auditoria();
        $auditoria->setIdUsuario($idUsuario);
        $auditoria->setAccion($accion);
        $auditoria->setTabla($tabla);
        $auditoria->setIdRegistro($idRegistro);
        $auditoria->setDatosAnteriores($datosAnteriores);
        $auditoria->setDatosNuevos($datosNuevos);
        $auditoria->setIp($this->obtenerIp());
        $auditoria->setUserAgent($_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido');

        return $this->dao->registrar($auditoria);
    }

    /**
     * Obtiene la IP del cliente
     */
    private function obtenerIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'Desconocido';
        }
    }

    /**
     * Obtiene lista de auditoría con filtros
     */
    public function listar($filtros = [])
    {
        return $this->dao->listar($filtros);
    }

    /**
     * Obtiene estadísticas
     */
    public function obtenerEstadisticas($fechaDesde = null, $fechaHasta = null)
    {
        return $this->dao->obtenerEstadisticas($fechaDesde, $fechaHasta);
    }
}

