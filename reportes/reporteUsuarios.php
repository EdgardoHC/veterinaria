<?php
// reportes/reporteUsuarios.php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// COMENTADO TEMPORALMENTE para que el PDF se muestre sin login
/*
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?page=login');
    exit;
}
*/

require_once __DIR__ . '/../vendor/autoload.php';
// [CAMBIO CLAVE 1/3]: Usamos el DAO de reportes segregado
require_once __DIR__ . '/../model/UsuarioReportesDAO.php'; 
// Eliminamos require_once UsuarioDAO.php y Usuario.php

use Dompdf\Dompdf;
use Dompdf\Options;

// [CAMBIO CLAVE 2/3]: Instanciamos el DAO de reportes
$dao = new UsuarioReportesDAO();
// [CAMBIO CLAVE 3/3]: Usamos el método listo para reportes (listarUsuariosConRol)
$usuarios = $dao->listarUsuariosConRol(); 
$fechaGeneracion = date('d/m/Y H:i');

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); 
$options->set('isRemoteEnabled', false); 
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);

$logoPath = __DIR__ . '/../view/vendor/bootstrap-4.6.2-dist/bootstrap-logo.png';
$logoData = null;
if (is_file($logoPath)) {
    $logoContents = file_get_contents($logoPath);
    if ($logoContents !== false) {
        $logoData = 'data:image/png;base64,' . base64_encode($logoContents);
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
        .encabezado { text-align: center; margin-bottom: 20px; }
        .encabezado h1 { margin: 5px 0; font-size: 20px; }
        .encabezado p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 6px; border: 1px solid #888; }
        th { background-color: #f0f0f0; text-align: left; }
        .no-registros { text-align: center; padding: 16px; }
        .footer { margin-top: 18px; font-size: 11px; text-align: right; }
    </style>
</head>
<body>
    <div class="encabezado">
        <?php if ($logoData): ?>
            <img src="<?= $logoData ?>" alt="Logo" style="max-height: 60px;" />
        <?php endif; ?>
        <h1>Informe de usuarios</h1>
        <p>Listado de cuentas activas dentro del sistema</p>
        <p>Generado: <?= $fechaGeneracion ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Nombre Completo</th>
                <th style="width: 30%;">Correo Electrónico</th>
                <th style="width: 20%;">Nombre Usuario</th>
                <th style="width: 20%;">Rol (Tipo)</th> </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td class="no-registros" colspan="4">No hay usuarios registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nombrecompleto'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($u['correoelectronico'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($u['nombreusuario'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($u['nombre_rol'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="footer">
        Documento generado automaticamente por el sistema.
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$nombreArchivo = 'informe_usuarios_' . date('Ymd_His') . '.pdf';
$dompdf->stream($nombreArchivo, ['Attachment' => false]);
exit;
?>