<?php
require_once __DIR__ . "/../model/Conexion.php";

/**
 * Clase para manejo de respaldos de base de datos
 * Grupo 6: Seguridad, auditoría y respaldo de datos
 */
class Respaldo
{
    private $conn;
    private $directorioRespaldo;
    private $host;
    private $user;
    private $pass;
    private $database;

    public function __construct()
    {
        $this->conn = Conexion::getInstance()->getConexion();
        $this->directorioRespaldo = __DIR__ . "/../backups/";
        
        // Crear directorio si no existe
        if (!is_dir($this->directorioRespaldo)) {
            mkdir($this->directorioRespaldo, 0755, true);
        }

        // Obtener credenciales de conexión (necesario para mysqldump)
        // Estas deberían estar en un archivo de configuración seguro
        $this->host = "localhost";
        $this->user = ""; // Se debe configurar
        $this->pass = ""; // Se debe configurar
        $this->database = ""; // Se debe configurar
    }

    /**
     * Realiza un respaldo de la base de datos
     */
    public function crearRespaldo($comentario = '')
    {
        try {
            // Obtener información de la conexión
            $dbName = $this->obtenerNombreBaseDatos();
            if (!$dbName) {
                return ['exito' => false, 'mensaje' => 'No se pudo determinar el nombre de la base de datos'];
            }

            // Nombre del archivo de respaldo
            $fecha = date('Y-m-d_H-i-s');
            $nombreArchivo = "respaldo_{$dbName}_{$fecha}.sql";
            $rutaCompleta = $this->directorioRespaldo . $nombreArchivo;

            // Obtener credenciales
            $host = $this->obtenerHost();
            $user = $this->obtenerUsuario();
            $pass = $this->obtenerPassword();

            // Crear respaldo usando mysqldump si está disponible
            $comando = $this->construirComandoMysqldump($host, $user, $pass, $dbName, $rutaCompleta);

            if ($comando) {
                exec($comando, $output, $returnVar);
                if ($returnVar === 0 && file_exists($rutaCompleta)) {
                    // Guardar metadatos del respaldo
                    $this->guardarMetadatos($nombreArchivo, $rutaCompleta, $comentario);
                    return [
                        'exito' => true,
                        'mensaje' => 'Respaldo creado exitosamente',
                        'archivo' => $nombreArchivo,
                        'ruta' => $rutaCompleta,
                        'tamaño' => filesize($rutaCompleta)
                    ];
                }
            }

            // Fallback: crear respaldo manual usando PHP
            return $this->crearRespaldoManual($dbName, $nombreArchivo, $rutaCompleta, $comentario);

        } catch (Exception $e) {
            error_log("Error al crear respaldo: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => 'Error al crear respaldo: ' . $e->getMessage()];
        }
    }

    /**
     * Crea respaldo manual usando consultas SQL
     */
    private function crearRespaldoManual($dbName, $nombreArchivo, $rutaCompleta, $comentario)
    {
        try {
            $archivo = fopen($rutaCompleta, 'w');
            if (!$archivo) {
                return ['exito' => false, 'mensaje' => 'No se pudo crear el archivo de respaldo'];
            }

            // Escribir encabezado
            fwrite($archivo, "-- Respaldo de base de datos: {$dbName}\n");
            fwrite($archivo, "-- Fecha: " . date('Y-m-d H:i:s') . "\n");
            if ($comentario) {
                fwrite($archivo, "-- Comentario: {$comentario}\n");
            }
            fwrite($archivo, "-- \n\n");
            fwrite($archivo, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // Obtener todas las tablas
            $tablas = $this->obtenerTablas();
            
            foreach ($tablas as $tabla) {
                $this->exportarTabla($archivo, $tabla);
            }

            fwrite($archivo, "\nSET FOREIGN_KEY_CHECKS=1;\n");
            fclose($archivo);

            // Guardar metadatos
            $this->guardarMetadatos($nombreArchivo, $rutaCompleta, $comentario);

            return [
                'exito' => true,
                'mensaje' => 'Respaldo creado exitosamente',
                'archivo' => $nombreArchivo,
                'ruta' => $rutaCompleta,
                'tamaño' => filesize($rutaCompleta)
            ];
        } catch (Exception $e) {
            if (isset($archivo)) {
                fclose($archivo);
            }
            error_log("Error en respaldo manual: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => 'Error al crear respaldo: ' . $e->getMessage()];
        }
    }

    /**
     * Exporta una tabla al archivo de respaldo
     */
    private function exportarTabla($archivo, $tabla)
    {
        fwrite($archivo, "-- Estructura de tabla: {$tabla}\n");
        
        // Exportar estructura
        $result = $this->conn->query("SHOW CREATE TABLE `{$tabla}`");
        if ($result) {
            $row = $result->fetch_assoc();
            fwrite($archivo, "DROP TABLE IF EXISTS `{$tabla}`;\n");
            fwrite($archivo, $row['Create Table'] . ";\n\n");
        }

        // Exportar datos
        $result = $this->conn->query("SELECT * FROM `{$tabla}`");
        if ($result && $result->num_rows > 0) {
            fwrite($archivo, "-- Datos de tabla: {$tabla}\n");
            
            while ($row = $result->fetch_assoc()) {
                $columnas = array_keys($row);
                $valores = array_values($row);
                
                // Escapar valores
                foreach ($valores as &$valor) {
                    if ($valor === null) {
                        $valor = 'NULL';
                    } else {
                        $valor = "'" . $this->conn->real_escape_string($valor) . "'";
                    }
                }
                
                $sql = "INSERT INTO `{$tabla}` (`" . implode("`, `", $columnas) . "`) VALUES (" . implode(", ", $valores) . ");\n";
                fwrite($archivo, $sql);
            }
            fwrite($archivo, "\n");
        }
    }

    /**
     * Obtiene lista de tablas en la base de datos
     */
    private function obtenerTablas()
    {
        $tablas = [];
        $result = $this->conn->query("SHOW TABLES");
        if ($result) {
            while ($row = $result->fetch_array()) {
                $tablas[] = $row[0];
            }
        }
        return $tablas;
    }

    /**
     * Lista todos los respaldos disponibles
     */
    public function listarRespaldos()
    {
        $respaldos = [];
        $archivos = glob($this->directorioRespaldo . "respaldo_*.sql");
        
        foreach ($archivos as $archivo) {
            $nombreArchivo = basename($archivo);
            $metadatos = $this->cargarMetadatos($nombreArchivo);
            
            $respaldos[] = [
                'archivo' => $nombreArchivo,
                'ruta' => $archivo,
                'tamaño' => filesize($archivo),
                'fecha' => date('Y-m-d H:i:s', filemtime($archivo)),
                'comentario' => $metadatos['comentario'] ?? '',
                'hash' => md5_file($archivo)
            ];
        }

        // Ordenar por fecha descendente
        usort($respaldos, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        return $respaldos;
    }

    /**
     * Restaura un respaldo
     */
    public function restaurarRespaldo($nombreArchivo)
    {
        try {
            $rutaCompleta = $this->directorioRespaldo . $nombreArchivo;
            
            if (!file_exists($rutaCompleta)) {
                return ['exito' => false, 'mensaje' => 'El archivo de respaldo no existe'];
            }

            // Leer el archivo SQL
            $sql = file_get_contents($rutaCompleta);
            if ($sql === false) {
                return ['exito' => false, 'mensaje' => 'No se pudo leer el archivo de respaldo'];
            }

            // Ejecutar el SQL en múltiples consultas
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            
            $this->conn->autocommit(false);
            
            try {
                foreach ($queries as $query) {
                    if (!empty($query) && !preg_match('/^--/', $query)) {
                        if (!$this->conn->query($query)) {
                            throw new Exception("Error en consulta: " . $this->conn->error);
                        }
                    }
                }
                
                $this->conn->commit();
                return ['exito' => true, 'mensaje' => 'Respaldo restaurado exitosamente'];
            } catch (Exception $e) {
                $this->conn->rollback();
                throw $e;
            } finally {
                $this->conn->autocommit(true);
            }

        } catch (Exception $e) {
            error_log("Error al restaurar respaldo: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => 'Error al restaurar respaldo: ' . $e->getMessage()];
        }
    }

    /**
     * Elimina un respaldo
     */
    public function eliminarRespaldo($nombreArchivo)
    {
        $rutaCompleta = $this->directorioRespaldo . $nombreArchivo;
        
        if (file_exists($rutaCompleta)) {
            $eliminado = unlink($rutaCompleta);
            if ($eliminado) {
                $this->eliminarMetadatos($nombreArchivo);
                return ['exito' => true, 'mensaje' => 'Respaldo eliminado exitosamente'];
            }
        }
        
        return ['exito' => false, 'mensaje' => 'No se pudo eliminar el respaldo'];
    }

    /**
     * Construye comando mysqldump
     */
    private function construirComandoMysqldump($host, $user, $pass, $dbName, $ruta)
    {
        // Verificar si mysqldump está disponible
        $mysqldumpPath = $this->encontrarMysqldump();
        if (!$mysqldumpPath) {
            return null;
        }

        $comando = escapeshellarg($mysqldumpPath);
        $comando .= " -h " . escapeshellarg($host);
        $comando .= " -u " . escapeshellarg($user);
        
        if ($pass) {
            $comando .= " -p" . escapeshellarg($pass);
        }
        
        $comando .= " " . escapeshellarg($dbName);
        $comando .= " > " . escapeshellarg($ruta) . " 2>&1";
        
        return $comando;
    }

    /**
     * Encuentra la ruta de mysqldump
     */
    private function encontrarMysqldump()
    {
        $rutas = [
            'mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp\\bin\\mysql\\mysql5.7.23\\bin\\mysqldump.exe',
        ];

        foreach ($rutas as $ruta) {
            if (is_executable($ruta) || shell_exec("which $ruta")) {
                return $ruta;
            }
        }

        return null;
    }

    /**
     * Obtiene el nombre de la base de datos actual
     */
    private function obtenerNombreBaseDatos()
    {
        $result = $this->conn->query("SELECT DATABASE() as db");
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['db'] ?? null;
        }
        return null;
    }

    /**
     * Obtiene el host de conexión
     */
    private function obtenerHost()
    {
        return $this->conn->host_info ?? "localhost";
    }

    /**
     * Obtiene el usuario de conexión
     */
    private function obtenerUsuario()
    {
        $result = $this->conn->query("SELECT USER() as user");
        if ($result) {
            $row = $result->fetch_assoc();
            return explode('@', $row['user'])[0] ?? null;
        }
        return null;
    }

    /**
     * Obtiene la contraseña (no se puede obtener desde la conexión, debe estar configurada)
     */
    private function obtenerPassword()
    {
        return $this->pass; // Debe configurarse en Conexion
    }

    /**
     * Guarda metadatos del respaldo
     */
    private function guardarMetadatos($nombreArchivo, $rutaCompleta, $comentario)
    {
        $archivoMeta = $this->directorioRespaldo . $nombreArchivo . '.meta';
        $metadatos = [
            'archivo' => $nombreArchivo,
            'fecha' => date('Y-m-d H:i:s'),
            'tamaño' => filesize($rutaCompleta),
            'comentario' => $comentario,
            'hash' => md5_file($rutaCompleta)
        ];
        file_put_contents($archivoMeta, json_encode($metadatos, JSON_PRETTY_PRINT));
    }

    /**
     * Carga metadatos del respaldo
     */
    private function cargarMetadatos($nombreArchivo)
    {
        $archivoMeta = $this->directorioRespaldo . $nombreArchivo . '.meta';
        if (file_exists($archivoMeta)) {
            $contenido = file_get_contents($archivoMeta);
            return json_decode($contenido, true) ?: [];
        }
        return [];
    }

    /**
     * Elimina metadatos del respaldo
     */
    private function eliminarMetadatos($nombreArchivo)
    {
        $archivoMeta = $this->directorioRespaldo . $nombreArchivo . '.meta';
        if (file_exists($archivoMeta)) {
            unlink($archivoMeta);
        }
    }
}

