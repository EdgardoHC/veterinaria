<?php
// view/reporteMascotasVista.php
require_once __DIR__ . '/../model/MascotaReportesDAO.php'; 

$dao = new MascotaReportesDAO();
$mascotas = $dao->listarMascotasConEncargados();
$datos_grafico = $dao->contarMascotasPorRaza();
$fechaGeneracion = date('d/m/Y H:i');

$labels = json_encode(array_column($datos_grafico, 'raza_nombre'));
$data = json_encode(array_column($datos_grafico, 'conteo'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vista Previa: Mascotas por Raza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* [ESTILOS COPIADOS DEL ORIGINAL: Paleta de colores, .encabezado, .data-table, .chart-container, .btn-toolbar] */
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
        .encabezado h1, .encabezado p { color: var(--color-texto-header); }
        .data-table { margin-top: 20px; font-size: 14px; border: 1px solid var(--color-borde) !important; }
        .data-table th { background-color: var(--color-secundario); color: var(--color-texto-header); border-color: var(--color-borde) !important; font-weight: bold; }
        .data-table tbody tr:nth-child(even) { background-color: var(--color-fondo-tabla); }
        .chart-container { margin: 30px auto; width: 70%; max-height: 450px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
        .btn-toolbar .btn-danger { background-color: #ff4d4d; border-color: #ff4d4d; }
        .btn-toolbar .btn-success { background-color: #9b4cafff; border-color: #3820a5ff; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="btn-toolbar justify-content-end">
             <div class="btn-group mr-3" role="group">
                <button class="btn btn-outline-primary" id="barBtn" onclick="toggleChartType('bar')">Gráfico de **Barra**</button>
                <button class="btn btn-outline-secondary" id="pieBtn" onclick="toggleChartType('pie')">Gráfico de **Pastel**</button>
            </div>
            <button class="btn btn-outline-info mr-3" id="toggleGraphButton">Ocultar Gráfica</button>

            <a href="../reportes/reporteMascotasPDF.php?type=pdf" target="_blank" class="btn btn-danger mr-2">Generar PDF 📄</a>
            <a href="../reportes/reporteMascotasExcel.php?type=excel" class="btn btn-success">Descargar Excel 📊</a>
        </div>
        
        <div class="card p-4 report-card">
            <div class="encabezado">
                <h1>🐶 Listado de Pacientes y Distribución por Raza (R2)</h1>
                <p>Generado: **<?= $fechaGeneracion ?>**</p>
            </div>
            
            <div class="chart-container" id="graphContainer">
                <h3 class="text-center mb-3 text-secondary">Distribución por Raza</h3>
                <canvas id="chartMascotas"></canvas>
            </div>

            <h3 class="mt-4 text-secondary">Pacientes Registrados</h3>
            <table class="table data-table table-sm">
                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Raza</th>
                        <th>Color</th>
                        <th>Fecha Nac.</th>
                        <th>Dueño</th>
                        <th>Teléfono Dueño</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mascotas as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['nombre_mascota'] ?? '') ?></td>
                            <td><strong class="text-info"><?= htmlspecialchars($m['nombre_raza'] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($m['color'] ?? '') ?></td>
                            <td><?= htmlspecialchars($m['fechanacimiento'] ?? '') ?></td>
                            <td><?= htmlspecialchars($m['nombre_encargado'] . ' ' . $m['apellido_encargado'] ?? '') ?></td>
                            <td><?= htmlspecialchars($m['telefonomovil'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        const ctxMascotas = document.getElementById('chartMascotas').getContext('2d');
        const chartLabelsMascotas = <?php echo $labels; ?>;
        const chartDataMascotas = <?php echo $data; ?>;
        let myChartMascotas;

        function createChartMascotas(type) {
            if (myChartMascotas) { myChartMascotas.destroy(); }
            
            const dataConfig = {
                labels: chartLabelsMascotas,
                datasets: [{
                    label: (type === 'bar' ? 'Conteo por Raza' : 'Distribución'),
                    data: chartDataMascotas,
                    backgroundColor: ['#cc2ed1ff', '#430150ff', '#3496cfff', '#155da1ff'],
                    borderWidth: 1
                }]
            };

            const options = (type === 'bar') ? { scales: { y: { beginAtZero: true } } } : {};
            
            myChartMascotas = new Chart(ctxMascotas, { type: type, data: dataConfig, options: options });
            
            document.getElementById('barBtn').classList.remove('btn-primary');
            document.getElementById('barBtn').classList.add('btn-outline-primary');
            document.getElementById('pieBtn').classList.remove('btn-secondary');
            document.getElementById('pieBtn').classList.add('btn-outline-secondary');
            if (type === 'bar') { document.getElementById('barBtn').classList.remove('btn-outline-primary'); document.getElementById('barBtn').classList.add('btn-primary'); } 
            else { document.getElementById('pieBtn').classList.remove('btn-outline-secondary'); document.getElementById('pieBtn').classList.add('btn-secondary'); }
        }

        window.toggleChartType = function(type) { if (chartLabelsMascotas.length > 0) createChartMascotas(type); };
        toggleChartType('bar'); 

        const button = document.getElementById('toggleGraphButton');
        const graphContainer = document.getElementById('graphContainer');

        button.addEventListener('click', () => {
            if (graphContainer.style.display === 'none') {
                graphContainer.style.display = 'block';
                button.textContent = 'Ocultar Gráfica';
                toggleChartType(myChartMascotas.config.type); 
            } else {
                graphContainer.style.display = 'none';
                button.textContent = 'Mostrar Gráfica';
            }
        });
    </script>
</body>
</html>