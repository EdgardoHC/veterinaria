<?php
// reportes/reporteRRHHExcel.php
// Genera el Excel del Detalle de Empleados.

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/RRHHReportesDAO.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dao = new RRHHReportesDAO();
$empleados = $dao->listarEmpleadosDetalle(); 
$fechaGeneracion = date('d/m/Y H:i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Personal');

$encabezados = ['Nombres', 'Apellidos', 'DNI', 'Puesto', 'Área', 'Fecha Ingreso'];
$columnas = ['A', 'B', 'C', 'D', 'E', 'F'];

foreach ($encabezados as $index => $titulo) {
    $sheet->setCellValue($columnas[$index] . '1', $titulo);
}
$sheet->getStyle('A1:F1')->getFont()->setBold(true);

$row = 2; 
foreach ($empleados as $e) {
    $sheet->setCellValue('A' . $row, $e['nombres'] ?? ''); 
    $sheet->setCellValue('B' . $row, $e['apellidos'] ?? '');
    $sheet->setCellValue('C' . $row, $e['dni'] ?? '');
    $sheet->setCellValue('D' . $row, $e['puesto'] ?? '');
    $sheet->setCellValue('E' . $row, $e['area'] ?? '');
    $sheet->setCellValue('F' . $row, $e['fechaingreso'] ?? '');
    $row++;
}

foreach ($columnas as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

$filename = 'informe_empleados_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>