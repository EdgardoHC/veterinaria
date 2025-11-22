<?php
// reportes/reporteUsuariosExcel.php

require_once __DIR__ . '/../vendor/autoload.php';
// [CAMBIO CLAVE 1/3]: Usamos el DAO de reportes segregado
require_once __DIR__ . '/../model/UsuarioReportesDAO.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // [CAMBIO CLAVE 2/3]: Instanciamos el DAO de reportes
    $dao = new UsuarioReportesDAO();
    // [CAMBIO CLAVE 3/3]: Usamos el método listo para reportes (listarUsuariosConRol)
    $usuarios = $dao->listarUsuariosConRol(); 

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Informe de Usuarios');

    // 1. Encabezados de la tabla
    $encabezados = ['ID', 'Nombre Completo', 'Correo Electrónico', 'Nombre Usuario', 'Rol'];
    $columnas = ['A', 'B', 'C', 'D', 'E'];
    
    foreach ($encabezados as $index => $titulo) {
        $sheet->setCellValue($columnas[$index] . '1', $titulo);
    }
    
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);

    // 2. Llenar la hoja de cálculo con los datos
    $row = 2; 
    foreach ($usuarios as $u) {
        $sheet->setCellValue('A' . $row, $u['idusuario'] ?? ''); 
        $sheet->setCellValue('B' . $row, $u['nombrecompleto'] ?? '');
        $sheet->setCellValue('C' . $row, $u['correoelectronico'] ?? '');
        $sheet->setCellValue('D' . $row, $u['nombreusuario'] ?? '');
        $sheet->setCellValue('E' . $row, $u['nombre_rol'] ?? ''); // Usamos el nombre del rol
        $row++;
    }

    // 3. Auto-ajustar el ancho de las columnas
    foreach ($columnas as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // 4. Configurar la respuesta HTTP para la descarga
    $filename = 'informe_usuarios_' . date('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

} catch (\Exception $e) {
    echo 'Error al generar el reporte Excel: ' . $e->getMessage();
}

exit;
?>