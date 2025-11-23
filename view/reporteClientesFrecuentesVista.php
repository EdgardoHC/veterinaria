<?php
// view/reporteClientesFrecuentesVista.php
require_once __DIR__ . '/../model/MascotaReportesDAO.php'; 

$dao = new MascotaReportesDAO();
$limite = 10; 
$frecuentes = $dao->listarClientesFrecuentes($limite); 
$fechaGeneracion = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vista Previa: Clientes Frecuentes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="btn-toolbar justify-content-end">
            <a href="../reportes/reporteClientesFrecuentesPDF.php?type=pdf" target="_blank" class="btn btn-danger mr-2">Generar PDF 📄</a>
            <a href="../reportes/reporteClientesFrecuentesExcel.php?type=excel" class="btn btn-success">Descargar Excel 📊</a>
        </div>
        
        <div class="card p-4 report-card">
            <div class="encabezado">
                <h1>🏆 Reporte de Clientes Frecuentes (Top <?= $limite ?> - R7)</h1>
                <p>Generado: **<?= $fechaGeneracion ?>**</p>
            </div>
            
            <h3 class="mt-4 text-secondary">Ranking de Visitas</h3>
            <table class="table data-table table-sm" style="width: 70%; margin: 0 auto;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mascota</th>
                        <th>Propietario</th>
                        <th>Total Visitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($frecuentes as $f): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($f['nombre_mascota'] . ' ' . $f['apellido_mascota'] ?? '') ?></td>
                            <td><?= htmlspecialchars($f['nombre_cliente'] . ' ' . $f['apellido_cliente'] ?? '') ?></td>
                            <td><strong class="text-primary"><?= htmlspecialchars($f['total_visitas'] ?? '0') ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>