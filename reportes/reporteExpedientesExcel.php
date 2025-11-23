<?php
// reportes/reporteExpedientesExcel.php
// Genera el Excel del Detalle de Expedientes Clínicos.

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dao = new ExpedienteReportesDAO();
$expedientes = $dao->listarDetallesExpediente(); 
$fechaGeneracion = date('d/m/Y H:i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Detalle Clínico');

$encabezados = ['Fecha', 'Mascota', 'Tipo Consulta', 'Diagnóstico', 'Peso (kg)', 'Altura (cm)', 'Veterinario'];
$columnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

foreach ($encabezados as $index => $titulo) {
    $sheet->setCellValue($columnas[$index] . '1', $titulo);
}
$sheet->getStyle('A1:G1')->getFont()->setBold(true);

$row = 2; 
foreach ($expedientes as $e) {
    $sheet->setCellValue('A' . $row, $e['fecha'] ?? ''); 
    $sheet->setCellValue('B' . $row, ($e['nombre_mascota'] ?? '') . ' ' . ($e['apellido_mascota'] ?? ''));
    $sheet->setCellValue('C' . $row, $e['tipo_expediente'] ?? '');
    $sheet->setCellValue('D' . $row, $e['diagnostico'] ?? '');
    $sheet->setCellValue('E' . $row, $e['peso'] ?? '');
    $sheet->setCellValue('F' . $row, $e['altura'] ?? '');
    $sheet->setCellValue('G' . $row, $e['veterinario_nombre'] ?? '');
    $row++;
}

foreach ($columnas as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

$filename = 'informe_expedientes_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>