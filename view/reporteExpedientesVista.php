<?php
// view/reporteExpedientesVista.php
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

$dao = new ExpedienteReportesDAO();
$expedientes = $dao->listarDetallesExpediente(); 
$fechaGeneracion = date('d/m/Y H:i');
// NOTA: Este reporte no tiene gráfico inmediato, pero mantenemos la estructura.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vista Previa: Detalle de Expedientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* [ESTILOS COPIADOS DEL ORIGINAL] */
        :root {
            --color-principal: #1de7ddff; 
            --color-secundario: #51f3ebff; 
            --color-fondo-tabla: #a7fffbff; 
            --color-texto-header: #ffffff;
            --color-borde: #0259caff;
        }
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f7f7f7; }
        .report-card { border: 1px solid var(--color-borde); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .encabezado { background-color: var(--color-principal); color: var(--color-texto-header); padding: 20px 0; border-bottom: 5px solid var(--color-secundario); margin-bottom: 20px; }
        .data-table { margin-top: 20px; font-size: 14px; border: 1px solid var(--color-borde) !important; }
        .data-table th { background-color: var(--color-secundario); color: var(--color-texto-header); border-color: var(--color-borde) !important; font-weight: bold; }
        .data-table tbody tr:nth-child(even) { background-color: var(--color-fondo-tabla); }
        /* Omito el contenedor de gráfico y botones de toggle, ya que es un reporte puramente tabular */
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="btn-toolbar justify-content-end">
            <a href="../reportes/reporteExpedientesPDF.php?type=pdf" target="_blank" class="btn btn-danger mr-2">Generar PDF 📄</a>
            <a href="../reportes/reporteExpedientesExcel.php?type=excel" class="btn btn-success">Descargar Excel 📊</a>
        </div>
        
        <div class="card p-4 report-card">
            <div class="encabezado">
                <h1>🩺 Historial de Consultas Médicas (R6)</h1>
                <p>Generado: **<?= $fechaGeneracion ?>**</p>
            </div>
            
            <h3 class="mt-4 text-secondary">Registros Clínicos</h3>
            <table class="table data-table table-sm">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Mascota</th>
                        <th>Tipo Consulta</th>
                        <th>Diagnóstico</th>
                        <th>Peso (kg)</th>
                        <th>Altura (cm)</th>
                        <th>Veterinario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expedientes as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['fecha'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['nombre_mascota'] . ' ' . $e['apellido_mascota'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['tipo_expediente'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['diagnostico'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['peso'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['altura'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['veterinario_nombre'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>