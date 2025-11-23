<?php
// reportes/reportePadecimientosExcel.php
// Genera el Excel del Resumen de Padecimientos Comunes.

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dao = new ExpedienteReportesDAO();
$conteo_padecimientos = $dao->contarPadecimientosComunes();
$fechaGeneracion = date('d/m/Y H:i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Padecimientos Resumen');

$encabezados = ['Padecimiento', 'Total de Casos'];
$columnas = ['A', 'B'];

foreach ($encabezados as $index => $titulo) {
    $sheet->setCellValue($columnas[$index] . '1', $titulo);
}
$sheet->getStyle('A1:B1')->getFont()->setBold(true);

$row = 2; 
foreach ($conteo_padecimientos as $p) {
    $sheet->setCellValue('A' . $row, $p['nombre_padecimiento'] ?? ''); 
    $sheet->setCellValue('B' . $row, $p['conteo'] ?? '');
    $row++;
}

foreach ($columnas as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

$filename = 'resumen_padecimientos_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>