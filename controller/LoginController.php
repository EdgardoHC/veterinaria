<?php
require_once __DIR__ . "/../util/Seguridad.php";
require_once __DIR__ . "/../model/UsuarioDAO.php";
require_once __DIR__ . "/../service/AuditoriaService.php";
require_once __DIR__ . "/../model/Auditoria.php";

header("Content-Type: application/json; charset=utf-8");

// Iniciar sesión segura
Seguridad::iniciarSesionSegura();

$dao = new UsuarioDAO();
$auditoriaService = new AuditoriaService();

if (isset($_POST['accion']) && $_POST['accion'] === "login") {
    $usuario = Seguridad::sanitizarEntrada($_POST['usuario'] ?? '');
    $pwd = $_POST['pwd'] ?? '';

    // Verificar intentos de login
    if (!Seguridad::verificarIntentosLogin($usuario)) {
        $tiempoRestante = Seguridad::obtenerTiempoBloqueo($usuario);
        $minutos = ceil($tiempoRestante / 60);
        echo json_encode([
            "ok" => false, 
            "msg" => "Demasiados intentos fallidos. Intente de nuevo en {$minutos} minutos."
        ]);
        exit;
    }

    $row = $dao->buscarPorEmailOApodo($usuario);

    if ($row && password_verify($pwd, $row['pwd'])) {
        // Limpiar intentos de login
        Seguridad::limpiarIntentosLogin($usuario);
        
        // Regenerar ID de sesión después de login exitoso
        session_regenerate_id(true);

        // Guardamos datos en la sesión
        $_SESSION['usuario'] = [
            "id" => $row['idUsuario'],
            "nombre" => $row['nombre'],
            "apellidos" => $row['apellidos'],
            "email" => $row['email'],
            "apodo" => $row['apodo']
        ];

        $_SESSION['ultima_actividad'] = time();
        $_SESSION['creado'] = time();

        // Registrar login en auditoría
        $auditoriaService->registrarAccion(
            $row['idUsuario'],
            Auditoria::ACCION_LOGIN,
            'usuarios',
            $row['idUsuario']
        );

        echo json_encode(["ok" => true]);
    } else {
        // Registrar intento fallido
        Seguridad::registrarIntentoFallido($usuario);
        
        // Registrar intento fallido en auditoría
        $auditoriaService->registrarAccion(
            null,
            Auditoria::ACCION_LOGIN,
            'usuarios',
            null,
            null,
            ['usuario_intentado' => $usuario, 'resultado' => 'fallido']
        );

        echo json_encode(["ok" => false, "msg" => "Usuario o contraseña incorrectos"]);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === "logout") {
    $idUsuario = $_SESSION['usuario']['id'] ?? null;
    
    // Registrar logout en auditoría antes de destruir sesión
    if ($idUsuario) {
        $auditoriaService->registrarAccion(
            $idUsuario,
            Auditoria::ACCION_LOGOUT,
            'sesion',
            null
        );
    }
    
    session_destroy();
    echo json_encode(["ok" => true]);
}
