<?php
// reportes/reporteMascotasExcel.php
// Genera el Excel del listado de Mascotas y Dueños.

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/MascotaReportesDAO.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dao = new MascotaReportesDAO();
$mascotas = $dao->listarMascotasConEncargados(); 
$fechaGeneracion = date('d/m/Y H:i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Mascotas y Dueños');

$encabezados = ['ID', 'Nombre Mascota', 'Raza', 'Dueño', 'Móvil Dueño', 'Fecha Nacimiento'];
$columnas = ['A', 'B', 'C', 'D', 'E', 'F'];

foreach ($encabezados as $index => $titulo) {
    $sheet->setCellValue($columnas[$index] . '1', $titulo);
}
$sheet->getStyle('A1:F1')->getFont()->setBold(true);

$row = 2; 
foreach ($mascotas as $m) {
    $sheet->setCellValue('A' . $row, $m['idmascota'] ?? ''); 
    $sheet->setCellValue('B' . $row, $m['nombre_mascota'] ?? '');
    $sheet->setCellValue('C' . $row, $m['nombre_raza'] ?? '');
    $sheet->setCellValue('D' . $row, ($m['nombre_encargado'] ?? '') . ' ' . ($m['apellido_encargado'] ?? ''));
    $sheet->setCellValue('E' . $row, $m['telefonomovil'] ?? '');
    $sheet->setCellValue('F' . $row, $m['fechanacimiento'] ?? '');
    $row++;
}

foreach ($columnas as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

$filename = 'informe_mascotas_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>