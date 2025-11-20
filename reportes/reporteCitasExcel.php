<?php
// reportes/reporteCitasExcel.php
// Genera el Excel del historial de Vacunas (Citas).

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dao = new ExpedienteReportesDAO();
$citas = $dao->listarProximasVacunas(); 
$fechaGeneracion = date('d/m/Y H:i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Citas y Vacunación');

$encabezados = ['Fecha Refuerzo', 'Mascota', 'Vacuna', 'Refuerzo #', 'Encargado', 'Móvil Encargado'];
$columnas = ['A', 'B', 'C', 'D', 'E', 'F'];

foreach ($encabezados as $index => $titulo) {
    $sheet->setCellValue($columnas[$index] . '1', $titulo);
}
$sheet->getStyle('A1:F1')->getFont()->setBold(true);

$row = 2; 
foreach ($citas as $c) {
    $sheet->setCellValue('A' . $row, $c['fechaproximavacuna'] ?? ''); 
    $sheet->setCellValue('B' . $row, ($c['nombre_mascota'] ?? '') . ' ' . ($c['apellido_mascota'] ?? '')); 
    $sheet->setCellValue('C' . $row, $c['nombre_vacuna'] ?? '');
    $sheet->setCellValue('D' . $row, $c['numerorefuerzo'] ?? '');
    $sheet->setCellValue('E' . $row, $c['nombre_encargado'] ?? '');
    $sheet->setCellValue('F' . $row, $c['telefonomovil'] ?? '');
    $row++;
}

foreach ($columnas as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

$filename = 'informe_citas_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>