<?php
// reportes/reporteClientesFrecuentesExcel.php
// Genera el Excel del Ranking de Clientes Frecuentes.

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/MascotaReportesDAO.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dao = new MascotaReportesDAO();
$limite = 10;
$frecuentes = $dao->listarClientesFrecuentes($limite); 
$fechaGeneracion = date('d/m/Y H:i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Clientes Frecuentes');

$encabezados = ['Mascota', 'Propietario', 'Total Visitas'];
$columnas = ['A', 'B', 'C'];

foreach ($encabezados as $index => $titulo) {
    $sheet->setCellValue($columnas[$index] . '1', $titulo);
}
$sheet->getStyle('A1:C1')->getFont()->setBold(true);

$row = 2; 
foreach ($frecuentes as $f) {
    $sheet->setCellValue('A' . $row, ($f['nombre_mascota'] ?? '') . ' ' . ($f['apellido_mascota'] ?? '')); 
    $sheet->setCellValue('B' . $row, ($f['nombre_cliente'] ?? '') . ' ' . ($f['apellido_cliente'] ?? ''));
    $sheet->setCellValue('C' . $row, $f['total_visitas'] ?? '');
    $row++;
}

foreach ($columnas as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

$filename = 'ranking_clientes_frecuentes_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>