<?php
// view/reporteRRHHVista.php
require_once __DIR__ . '/../model/RRHHReportesDAO.php'; 

$dao = new RRHHReportesDAO();
$empleados = $dao->listarEmpleadosDetalle(); 
$datos_grafico = $dao->contarEmpleadosPorArea();
$fechaGeneracion = date('d/m/Y H:i');

$labels = json_encode(array_column($datos_grafico, 'area_nombre'));
$data = json_encode(array_column($datos_grafico, 'conteo'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vista Previa: Personal y RRHH</title>
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
            <button class="btn btn-outline-info mr-3" id="toggleGraphButton">Ocultar Gráfica</button>

            <a href="../reportes/reporteRRHHPDF.php?type=pdf" target="_blank" class="btn btn-danger mr-2">Generar PDF 📄</a>
            <a href="../reportes/reporteRRHHExcel.php?type=excel" class="btn btn-success">Descargar Excel 📊</a>
        </div>
        
        <div class="card p-4 report-card">
            <div class="encabezado">
                <h1>👨‍💼 Reporte de Personal y RRHH (R10)</h1>
                <p>Generado: **<?= $fechaGeneracion ?>**</p>
            </div>

            <div class="chart-container" id="graphContainer">
                <h3 class="text-center mb-3 text-secondary">Distribución de Personal por Área</h3>
                <canvas id="chartArea"></canvas>
            </div>
            
            <h3 class="mt-4 text-secondary">Listado Detallado de Empleados</h3>
            <table class="table data-table table-sm">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>DNI</th>
                        <th>Puesto</th>
                        <th>Área</th>
                        <th>Fecha Ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['nombres'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['apellidos'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['dni'] ?? '') ?></td>
                            <td><strong class="text-secondary"><?= htmlspecialchars($e['puesto'] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($e['area'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['fechaingreso'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        const ctxArea = document.getElementById('chartArea').getContext('2d');
        const chartLabelsArea = <?php echo $labels; ?>;
        const chartDataArea = <?php echo $data; ?>;
        let myChartArea;

        function createChartArea(type) {
            if (myChartArea) { myChartArea.destroy(); }
            
            const dataConfig = {
                labels: chartLabelsArea,
                datasets: [{
                    label: (type === 'bar' ? 'Conteo por Área' : 'Distribución'),
                    data: chartDataArea,
                    backgroundColor: ['#d9ead3', '#a9d18e', '#6aa84f', '#38761d'],
                    borderWidth: 1
                }]
            };

            const options = (type === 'bar') ? { scales: { y: { beginAtZero: true } } } : {};
            
            myChartArea = new Chart(ctxArea, { type: type, data: dataConfig, options: options });
            
            document.getElementById('barBtn').classList.remove('btn-primary');
            document.getElementById('barBtn').classList.add('btn-outline-primary');
            document.getElementById('pieBtn').classList.remove('btn-secondary');
            document.getElementById('pieBtn').classList.add('btn-outline-secondary');
            if (type === 'bar') { document.getElementById('barBtn').classList.remove('btn-outline-primary'); document.getElementById('barBtn').classList.add('btn-primary'); } 
            else { document.getElementById('pieBtn').classList.remove('btn-outline-secondary'); document.getElementById('pieBtn').classList.add('btn-secondary'); }
        }

        window.toggleChartType = function(type) { if (chartLabelsArea.length > 0) createChartArea(type); };
        toggleChartType('bar'); 
        
        const button = document.getElementById('toggleGraphButton');
        const graphContainer = document.getElementById('graphContainer');

        button.addEventListener('click', () => {
            if (graphContainer.style.display === 'none') {
                graphContainer.style.display = 'block';
                button.textContent = 'Ocultar Gráfica';
                toggleChartType(myChartArea.config.type); 
            } else {
                graphContainer.style.display = 'none';
                button.textContent = 'Mostrar Gráfica';
            }
        });
    </script>
</body>
</html>