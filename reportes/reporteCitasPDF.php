<?php
// reportes/reporteCitasPDF.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$dao = new ExpedienteReportesDAO();
$citas = $dao->listarProximasVacunas(); 
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
            .encabezado { text-align: center; margin-bottom: 10px; background-color: #1de7ddff; color: white; padding: 10px; }
            h1 { font-size: 16px; margin: 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
            th { background-color: #51f3ebff; color: #333; font-weight: bold; }
            tr:nth-child(even) { background-color: #a7fffbff; }
        </style>
    </head>
    <body>
        <div class="encabezado">
            <h1>Informe de Citas y Vacunación (Historial)</h1>
            <p>Generado: ' . $fechaGeneracion . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Fecha Refuerzo</th>
                    <th>Mascota</th>
                    <th>Vacuna</th>
                    <th>Refuerzo #</th>
                    <th>Encargado</th>
                    <th>Móvil Encargado</th>
                </tr>
            </thead>
            <tbody>';
    
    if (empty($citas)) {
        $html .= '<tr><td colspan="6" style="text-align: center;">No hay registros de vacunación.</td></tr>';
    } else {
        foreach ($citas as $c) {
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($c['fechaproximavacuna'] ?? '') . '</td>
                    <td>' . htmlspecialchars($c['nombre_mascota'] . ' ' . $c['apellido_mascota'] ?? '') . '</td>
                    <td>' . htmlspecialchars($c['nombre_vacuna'] ?? '') . '</td>
                    <td>' . htmlspecialchars($c['numerorefuerzo'] ?? '') . '</td>
                    <td>' . htmlspecialchars($c['nombre_encargado'] ?? '') . '</td>
                    <td>' . htmlspecialchars($c['telefonomovil'] ?? '') . '</td>
                </tr>';
        }
    }
    
    $html .= '
            </tbody>
        </table>
    </body>
    </html>';

    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $nombreArchivo = 'informe_citas_' . date('Ymd_His') . '.pdf';
    $dompdf->stream($nombreArchivo, ['Attachment' => false]);
    exit;
?>