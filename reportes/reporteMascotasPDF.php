<?php
// reportes/reporteMascotasPDF.php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/MascotaReportesDAO.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$dao = new MascotaReportesDAO();
$mascotas = $dao->listarMascotasConEncargados(); 
$fechaGeneracion = date('d/m/Y H:i');

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); 
$dompdf = new Dompdf($options);
    
// --- AQUI VA LA PLANTILLA HTML COMPLETA ---
$html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 8px; }
            .encabezado { text-align: center; background-color: #1de7ddff; color: white; padding: 10px; }
            h1 { font-size: 14px; margin: 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #ccc; padding: 3px; text-align: left; }
            th { background-color: #51f3ebff; color: #333; font-weight: bold; }
            tr:nth-child(even) { background-color: #a7fffbff; }
        </style>
    </head>
    <body>
        <div class="encabezado">
            <h1>Informe de Pacientes y Propietarios</h1>
            <p>Generado: ' . $fechaGeneracion . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Mascota</th>
                    <th>Raza</th>
                    <th>Dueño</th>
                    <th>Móvil Dueño</th>
                    <th>F. Nac.</th>
                </tr>
            </thead>
            <tbody>';
    
    if (empty($mascotas)) {
        $html .= '<tr><td colspan="6" style="text-align: center;">No hay mascotas registradas.</td></tr>';
    } else {
        foreach ($mascotas as $m) {
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($m['idmascota'] ?? '') . '</td>
                    <td>' . htmlspecialchars($m['nombre_mascota'] ?? '') . '</td>
                    <td>' . htmlspecialchars($m['nombre_raza'] ?? '') . '</td>
                    <td>' . htmlspecialchars(($m['nombre_encargado'] ?? '') . ' ' . ($m['apellido_encargado'] ?? '')) . '</td>
                    <td>' . htmlspecialchars($m['telefonomovil'] ?? '') . '</td>
                    <td>' . htmlspecialchars($m['fechanacimiento'] ?? '') . '</td>
                </tr>';
        }
    }
    $html .= '</tbody></table></body></html>';

    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $nombreArchivo = 'informe_mascotas_' . date('Ymd_His') . '.pdf';
    $dompdf->stream($nombreArchivo, ['Attachment' => false]);
    exit;
?>