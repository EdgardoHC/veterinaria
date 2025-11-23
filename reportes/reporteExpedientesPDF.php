<?php
// reportes/reporteExpedientesPDF.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$dao = new ExpedienteReportesDAO();
$expedientes = $dao->listarDetallesExpediente(); 
$fechaGeneracion = date('d/m/Y H:i');

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); 
$dompdf = new Dompdf($options);
    
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
            <h1>Historial de Consultas Médicas</h1>
            <p>Generado: ' . $fechaGeneracion . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Mascota</th>
                    <th>Tipo Consulta</th>
                    <th>Diagnóstico</th>
                    <th>Peso (kg)</th>
                    <th>Altura (cm)</th>
                    <th>Veterinario</th>
                </tr>
            </thead>
            <tbody>';
    
    if (empty($expedientes)) {
        $html .= '<tr><td colspan="7" style="text-align: center;">No hay registros de expedientes.</td></tr>';
    } else {
        foreach ($expedientes as $e) {
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($e['fecha'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['nombre_mascota'] . ' ' . $e['apellido_mascota'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['tipo_expediente'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['diagnostico'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['peso'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['altura'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['veterinario_nombre'] ?? '') . '</td>
                </tr>';
        }
    }
    $html .= '</tbody></table></body></html>';

    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $nombreArchivo = 'informe_expedientes_' . date('Ymd_His') . '.pdf';
    $dompdf->stream($nombreArchivo, ['Attachment' => false]);
    exit;
?>