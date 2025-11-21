<?php
require_once __DIR__ . "/../util/Seguridad.php";
require_once __DIR__ . "/../model/UsuarioDAO.php";
require_once __DIR__ . "/../service/AuditoriaService.php";
require_once __DIR__ . "/../model/Auditoria.php";

header("Content-Type: application/json; charset=utf-8");

// Iniciar sesión segura
Seguridad::iniciarSesionSegura();

// Se repite header("Content-Type: application/json; charset=utf-8"); aquí, pero lo mantenemos para reflejar la integración de ambos códigos.

$dao = new UsuarioDAO();
$auditoriaService = new AuditoriaService();


if (isset($_POST['accion']) && $_POST['accion'] === "login") {
    $usuario = Seguridad::sanitizarEntrada($_POST['usuario'] ?? '');
    $pwd = $_POST['pwd'] ?? '';

    // Limpiar los intentos fallidos de login para el usuario
    Seguridad::limpiarIntentosLogin($usuario);

    // Verificar intentos de login (Funcionalidad de Seguridad)
    if (!Seguridad::verificarIntentosLogin($usuario)) {
        $tiempoRestante = Seguridad::obtenerTiempoBloqueo($usuario);
        $minutos = ceil($tiempoRestante / 60);
        echo json_encode([
            "ok" => false, 
            "msg" => "Demasiados intentos fallidos. Intente de nuevo en {$minutos} minutos."
        ]);
        exit;
    }
}

$accion = $_POST['accion'] ?? ''; // Aseguramos que la acción exista

if ($accion === "login") {

    $usuario = $_POST['usuario'] ?? "";
    $pwd     = $_POST['pwd'] ?? "";

    if ($usuario === "" || $pwd === "") {
        echo json_encode(["ok" => false, "msg" => "Usuario y contraseña son obligatorios"]);
        exit;
    }

    $row = $dao->buscarPorEmailONombreUsuario($usuario);

    if (!$row || $row['estado'] != 1) {
        // Registrar intento fallido (para prevenir ataques de enumeración de usuarios)
        Seguridad::registrarIntentoFallido($usuario);
        echo json_encode(["ok" => false, "msg" => "Usuario o contraseña incorrectos"]);
        exit;
    }

    $loginExitoso = false;

    // 1. Verificar Hash (método moderno)
    if (password_verify($pwd, $row['contrasena'])) {
        $loginExitoso = true;
    } 
    // 2. Verificar Contraseña sin Hash (para migración de usuarios antiguos)
    else if ($pwd === $row['contrasena']) {
        $loginExitoso = true;
        
        try {
            // Se actualiza la contraseña a hash
            // CÓDIGO CORREGIDO: $row['idUsuario'] en lugar de $row['idusuario']
            $dao->actualizarContrasena($row['idUsuario'], $pwd);
        } catch (Exception $e) {
            // CÓDIGO CORREGIDO: $row['idUsuario'] en lugar de $row['idusuario']
            error_log("Error al migrar hash de usuario: " . $row['idUsuario']);
        }
    }

    if ($loginExitoso) {
        
        // Limpiar intentos de login (Funcionalidad de Seguridad)
        Seguridad::limpiarIntentosLogin($usuario);
        
        // Regenerar ID de sesión después de login exitoso (Funcionalidad de Seguridad)
        session_regenerate_id(true);

        // Guardamos datos en la sesión
        $_SESSION['usuario'] = [
            // CÓDIGO CORREGIDO: $row['idusuario'] en lugar de $row['idUsuario']
            "id"         => $row['idusuario'] ?? $row['idUsuario'] ?? '',
            "nombre"     => $row['nombrecompleto'] ?? '',
            "usuario"    => $row['nombreusuario'] ?? '',
            "email"      => $row['correoelectronico'] ?? '',
            "rol_nombre" => $row['nombre_rol'] ?? '' // El nombre de la columna de roles es 'nombre_rol'
        ];

        $_SESSION['ultima_actividad'] = time();
        $_SESSION['creado'] = time();

        // Registrar login en auditoría
        $auditoriaService->registrarAccion(
            // CÓDIGO CORREGIDO: $row['idUsuario'] en lugar de $row['idusuario']
            $row['idUsuario'],
            Auditoria::ACCION_LOGIN,
            'usuarios',
            // CÓDIGO CORREGIDO: $row['idUsuario'] en lugar de $row['idusuario']
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
    exit;
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
    exit;
}

// Bloque que maneja la acción no especificada o no válida
if (!isset($_POST['accion'])) {
    echo json_encode(["ok" => false, "msg" => "Acción no especificada"]);
    exit;
}

echo json_encode(["ok" => false, "msg" => "Acción no válida"]);