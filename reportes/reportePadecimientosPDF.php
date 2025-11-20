<?php
// reportes/reportePadecimientosPDF.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$dao = new ExpedienteReportesDAO();
$conteo_padecimientos = $dao->contarPadecimientosComunes();
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
            body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; }
            .encabezado { text-align: center; background-color: #1de7ddff; color: white; padding: 10px; }
            h1 { font-size: 16px; margin: 0; }
            table { width: 50%; margin: 20px auto; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
            th { background-color: #51f3ebff; color: #333; font-weight: bold; }
            tr:nth-child(even) { background-color: #a7fffbff; }
        </style>
    </head>
    <body>
        <div class="encabezado">
            <h1>Resumen de Padecimientos Comunes</h1>
            <p>Generado: ' . $fechaGeneracion . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Padecimiento</th>
                    <th>Total de Casos</th>
                </tr>
            </thead>
            <tbody>';
    
    if (empty($conteo_padecimientos)) {
        $html .= '<tr><td colspan="2" style="text-align: center;">No hay padecimientos comunes registrados.</td></tr>';
    } else {
        foreach ($conteo_padecimientos as $p) {
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($p['nombre_padecimiento'] ?? '') . '</td>
                    <td>' . htmlspecialchars($p['conteo'] ?? '') . '</td>
                </tr>';
        }
    }
    $html .= '</tbody></table></body></html>';

    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $nombreArchivo = 'resumen_padecimientos_' . date('Ymd_His') . '.pdf';
    $dompdf->stream($nombreArchivo, ['Attachment' => false]);
    exit;
?>