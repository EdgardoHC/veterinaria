<?php
/**
 * Clase de utilidades de seguridad
 * Grupo 6: Seguridad, auditoría y respaldo de datos
 */
class Seguridad
{
    // Configuración de sesión
    const SESSION_TIMEOUT = 1800; // 30 minutos en segundos
    const MAX_LOGIN_ATTEMPTS = 5; // Intentos máximos de login
    const LOGIN_ATTEMPTS_TIMEOUT = 900; // 15 minutos de bloqueo

    /**
     * Inicializa la sesión con configuraciones seguras
     */
    public static function iniciarSesionSegura()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Configuraciones de seguridad para sesiones
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
            
            // Regenerar ID de sesión periódicamente para prevenir hijacking
            if (!isset($_SESSION['creado'])) {
                $_SESSION['creado'] = time();
                session_regenerate_id(true);
            } elseif (time() - $_SESSION['creado'] > 600) { // Regenerar cada 10 minutos
                $_SESSION['creado'] = time();
                session_regenerate_id(true);
            }

            // Verificar timeout de sesión
            if (isset($_SESSION['ultima_actividad']) && 
                (time() - $_SESSION['ultima_actividad'] > self::SESSION_TIMEOUT)) {
                session_unset();
                session_destroy();
                session_start();
                return false;
            }
            
            $_SESSION['ultima_actividad'] = time();
        }
        return true;
    }

    /**
     * Genera un token CSRF
     */
    public static function generarTokenCSRF()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valida un token CSRF
     */
    public static function validarTokenCSRF($token)
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitiza entrada para prevenir XSS
     */
    public static function sanitizarEntrada($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizarEntrada'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Verifica intentos de login fallidos
     */
    public static function verificarIntentosLogin($usuario)
    {
        $key = 'login_attempts_' . md5($usuario);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['intentos' => 0, 'ultimo_intento' => 0];
        }

        $intentos = $_SESSION[$key];
        
        // Resetear si han pasado más de 15 minutos
        if (time() - $intentos['ultimo_intento'] > self::LOGIN_ATTEMPTS_TIMEOUT) {
            $_SESSION[$key] = ['intentos' => 0, 'ultimo_intento' => 0];
            return true;
        }

        // Verificar si excedió los intentos máximos
        if ($intentos['intentos'] >= self::MAX_LOGIN_ATTEMPTS) {
            return false;
        }

        return true;
    }

    /**
     * Registra un intento de login fallido
     */
    public static function registrarIntentoFallido($usuario)
    {
        $key = 'login_attempts_' . md5($usuario);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['intentos' => 0, 'ultimo_intento' => 0];
        }

        $_SESSION[$key]['intentos']++;
        $_SESSION[$key]['ultimo_intento'] = time();
    }

    /**
     * Limpia intentos de login (usado después de un login exitoso)
     */
    public static function limpiarIntentosLogin($usuario)
    {
        $key = 'login_attempts_' . md5($usuario);
        unset($_SESSION[$key]);
    }

    /**
     * Obtiene el tiempo restante de bloqueo
     */
    public static function obtenerTiempoBloqueo($usuario)
    {
        $key = 'login_attempts_' . md5($usuario);
        
        if (!isset($_SESSION[$key])) {
            return 0;
        }

        $intentos = $_SESSION[$key];
        $tiempoTranscurrido = time() - $intentos['ultimo_intento'];
        $tiempoRestante = self::LOGIN_ATTEMPTS_TIMEOUT - $tiempoTranscurrido;
        
        return max(0, $tiempoRestante);
    }

    /**
     * Valida y sanitiza email
     */
    public static function validarEmail($email)
    {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? $email : false;
    }

    /**
     * Valida contraseña (mínimo 8 caracteres, al menos una mayúscula, una minúscula y un número)
     */
    public static function validarPassword($password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        return true;
    }
}

