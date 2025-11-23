<?php
// reportes/reporteRRHHPDF.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/RRHHReportesDAO.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$dao = new RRHHReportesDAO();
$empleados = $dao->listarEmpleadosDetalle(); 
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
            <h1>Informe Detallado de Personal y RRHH</h1>
            <p>Generado: ' . $fechaGeneracion . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Puesto</th>
                    <th>Área</th>
                    <th>Fecha Ingreso</th>
                </tr>
            </thead>
            <tbody>';
    
    if (empty($empleados)) {
        $html .= '<tr><td colspan="6" style="text-align: center;">No hay personal registrado.</td></tr>';
    } else {
        foreach ($empleados as $e) {
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($e['nombres'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['apellidos'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['dni'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['puesto'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['area'] ?? '') . '</td>
                    <td>' . htmlspecialchars($e['fechaingreso'] ?? '') . '</td>
                </tr>';
        }
    }
    $html .= '</tbody></table></body></html>';

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$nombreArchivo = 'informe_empleados_' . date('Ymd_His') . '.pdf';
$dompdf->stream($nombreArchivo, ['Attachment' => false]);
exit;
?>