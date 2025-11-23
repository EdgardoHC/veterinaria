<?php
// view/graficosUsuarios.php

require_once __DIR__ . '/../model/UsuarioReportesDAO.php';

$dao = new UsuarioReportesDAO();
$datos_grafico = $dao->contarUsuariosPorRol(); 

$labels = json_encode(array_column($datos_grafico, 'rol_nombre'));
$data = json_encode(array_column($datos_grafico, 'conteo'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Distribución de Usuarios</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .container { width: 80%; margin: 50px auto; }
        #chartCanvasContainer { margin-top: 20px; max-height: 400px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Distribución de Usuarios por Rol</h1>
        
        <div class="mb-3">
            <button class="btn btn-primary" onclick="toggleChartType('bar')">Gráfico de Barras</button>
            <button class="btn btn-secondary" onclick="toggleChartType('pie')">Gráfico de Pastel</button>
            <a href="../index.php" class="btn btn-outline-dark float-right">Volver a Inicio</a>
        </div>

        <div id="chartCanvasContainer">
            <canvas id="chartUsuarios"></canvas>
        </div>
        
    </div>

    <script>
        const ctx = document.getElementById('chartUsuarios').getContext('2d');
        const chartLabels = <?php echo $labels; ?>;
        const chartData = <?php echo $data; ?>;
        let myChart; // Variable global para la instancia del gráfico

        function createChart(type) {
            if (myChart) {
                myChart.destroy(); // Destruye la instancia anterior
            }
            
            const dataConfig = {
                labels: chartLabels,
                datasets: [{
                    label: (type === 'bar' ? 'Cantidad de Usuarios' : 'Distribución'),
                    data: chartData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    borderWidth: 1
                }]
            };

            // Las opciones de escala solo aplican a gráficos de barras
            const options = (type === 'bar') ? {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            } : {
                responsive: true,
                maintainAspectRatio: false,
            };
            
            myChart = new Chart(ctx, {
                type: type,
                data: dataConfig,
                options: options
            });
        }

        window.toggleChartType = function(type) {
            if (chartLabels.length > 0 && chartData.length > 0) {
                createChart(type);
            } else {
                ctx.font = "16px Arial";
                ctx.fillStyle = "#888";
                ctx.fillText("No hay datos disponibles para el gráfico.", 10, 50);
            }
        }
        
        // Inicializa con gráfico de barras al cargar
        toggleChartType('bar'); 
    </script>
</body>
</html>