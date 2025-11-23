<?php
// reportes/reporteClientesFrecuentesPDF.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/MascotaReportesDAO.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$dao = new MascotaReportesDAO();
$limite = 10;
$frecuentes = $dao->listarClientesFrecuentes($limite); 
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
            table { width: 70%; margin: 20px auto; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
            th { background-color: #51f3ebff; color: #333; font-weight: bold; }
            tr:nth-child(even) { background-color: #a7fffbff; }
        </style>
    </head>
    <body>
        <div class="encabezado">
            <h1>Ranking de Clientes Frecuentes (Top ' . $limite . ')</h1>
            <p>Generado: ' . $fechaGeneracion . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mascota</th>
                    <th>Propietario</th>
                    <th>Total Visitas</th>
                </tr>
            </thead>
            <tbody>';
    
    if (empty($frecuentes)) {
        $html .= '<tr><td colspan="3" style="text-align: center;">No hay suficientes registros para el ranking.</td></tr>';
    } else {
        foreach ($frecuentes as $f) {
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($f['nombre_mascota'] . ' ' . $f['apellido_mascota'] ?? '') . '</td>
                    <td>' . htmlspecialchars($f['nombre_cliente'] . ' ' . $f['apellido_cliente'] ?? '') . '</td>
                    <td>' . htmlspecialchars($f['total_visitas'] ?? '') . '</td>
                </tr>';
        }
    }
    $html .= '</tbody></table></body></html>';

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$nombreArchivo = 'ranking_clientes_frecuentes_' . date('Ymd_His') . '.pdf';
$dompdf->stream($nombreArchivo, ['Attachment' => false]);
exit;
?>