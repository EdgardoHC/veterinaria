<?php
/**
 * Modelo de Auditoría
 * Grupo 6: Seguridad, auditoría y respaldo de datos
 */
class Auditoria
{
    private $idAuditoria;
    private $idUsuario;
    private $accion;
    private $tabla;
    private $idRegistro;
    private $datosAnteriores;
    private $datosNuevos;
    private $ip;
    private $userAgent;
    private $fecha;

    // Constantes de acciones
    const ACCION_LOGIN = 'LOGIN';
    const ACCION_LOGOUT = 'LOGOUT';
    const ACCION_CREAR = 'CREAR';
    const ACCION_ACTUALIZAR = 'ACTUALIZAR';
    const ACCION_ELIMINAR = 'ELIMINAR';
    const ACCION_CONSULTAR = 'CONSULTAR';
    const ACCION_RESPALDO = 'RESPALDO';
    const ACCION_RESTAURAR = 'RESTAURAR';

    // Getters y Setters
    public function getIdAuditoria()
    {
        return $this->idAuditoria;
    }

    public function setIdAuditoria($idAuditoria)
    {
        $this->idAuditoria = $idAuditoria;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getAccion()
    {
        return $this->accion;
    }

    public function setAccion($accion)
    {
        $this->accion = $accion;
    }

    public function getTabla()
    {
        return $this->tabla;
    }

    public function setTabla($tabla)
    {
        $this->tabla = $tabla;
    }

    public function getIdRegistro()
    {
        return $this->idRegistro;
    }

    public function setIdRegistro($idRegistro)
    {
        $this->idRegistro = $idRegistro;
    }

    public function getDatosAnteriores()
    {
        return $this->datosAnteriores;
    }

    public function setDatosAnteriores($datosAnteriores)
    {
        $this->datosAnteriores = is_array($datosAnteriores) 
            ? json_encode($datosAnteriores, JSON_UNESCAPED_UNICODE) 
            : $datosAnteriores;
    }

    public function getDatosNuevos()
    {
        return $this->datosNuevos;
    }

    public function setDatosNuevos($datosNuevos)
    {
        $this->datosNuevos = is_array($datosNuevos) 
            ? json_encode($datosNuevos, JSON_UNESCAPED_UNICODE) 
            : $datosNuevos;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}

