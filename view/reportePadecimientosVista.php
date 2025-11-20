<?php
// view/reportePadecimientosVista.php
require_once __DIR__ . '/../model/ExpedienteReportesDAO.php'; 

$dao = new ExpedienteReportesDAO();
$datos_grafico = $dao->contarPadecimientosComunes(); 
$fechaGeneracion = date('d/m/Y H:i');

$labels = json_encode(array_column($datos_grafico, 'nombre_padecimiento'));
$data = json_encode(array_column($datos_grafico, 'conteo'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vista Previa: Padecimientos Comunes</title>
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
        .chart-container { margin: 30px auto; width: 70%; max-height: 450px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="btn-toolbar justify-content-end">
            <div class="btn-group mr-3" role="group">
                <button class="btn btn-outline-primary" id="barBtn" onclick="toggleChartType('bar')">Gráfico de **Barra**</button>
                <button class="btn btn-outline-secondary" id="pieBtn" onclick="toggleChartType('doughnut')">Gráfico de **Anillo**</button>
            </div>
            <a href="../reportes/reportePadecimientosPDF.php?type=pdf" target="_blank" class="btn btn-danger mr-2">Generar PDF Resumen 📄</a>
            <a href="../reportes/reportePadecimientosExcel.php?type=excel" class="btn btn-success">Descargar Excel Resumen 📊</a>
        </div>
        
        <div class="card p-4 report-card">
            <div class="encabezado">
                <h1>🔬 Reporte de Padecimientos Más Comunes (R8)</h1>
                <p>Generado: **<?= $fechaGeneracion ?>**</p>
            </div>
            
            <div class="chart-container" id="graphContainer">
                <h3 class="text-center mb-3 text-secondary">Frecuencia de Diagnósticos</h3>
                <canvas id="chartPadecimientos"></canvas>
            </div>
            
            <h3 class="mt-4 text-secondary">Conteo de Registros por Padecimiento</h3>
            <table class="table data-table table-sm" style="width: 50%; margin: 0 auto;">
                <thead>
                    <tr>
                        <th>Padecimiento</th>
                        <th>Conteo de Casos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_grafico as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nombre_padecimiento'] ?? '') ?></td>
                            <td><?= htmlspecialchars($p['conteo'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        const ctxPade = document.getElementById('chartPadecimientos').getContext('2d');
        const chartLabelsPade = <?php echo $labels; ?>;
        const chartDataPade = <?php echo $data; ?>;
        let myChartPade;

        function createChartPade(type) {
            if (myChartPade) { myChartPade.destroy(); }
            // Lógica de datos (copiada del original)
            const dataConfig = {
                labels: chartLabelsPade, datasets: [{
                    label: 'Conteo de Casos', data: chartDataPade,
                    backgroundColor: ['#85a661', '#4CAF50', '#b6d7a8', '#38761d', '#99ccff', '#ffb366'],
                    borderWidth: 1
                }]
            };

            const options = (type === 'bar') ? { scales: { y: { beginAtZero: true } } } : {};
            myChartPade = new Chart(ctxPade, { type: type, data: dataConfig, options: options });
            
            // Lógica de botones (simplificada)
            document.getElementById('barBtn').classList.toggle('btn-primary', type === 'bar');
            document.getElementById('barBtn').classList.toggle('btn-outline-primary', type !== 'bar');
            document.getElementById('pieBtn').classList.toggle('btn-secondary', type === 'doughnut');
            document.getElementById('pieBtn').classList.toggle('btn-outline-secondary', type !== 'doughnut');
        }

        window.toggleChartType = function(type) { if (chartLabelsPade.length > 0) createChartPade(type); };
        toggleChartType('bar'); 
    </script>
</body>
</html>