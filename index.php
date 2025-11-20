<?php
require_once __DIR__ . "/util/Seguridad.php";

// Iniciar sesión segura globalmente
Seguridad::iniciarSesionSegura();

require_once __DIR__ . "/router.php"; // incluir el enrutador
