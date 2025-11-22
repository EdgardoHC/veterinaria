<?php
// view/reporteUsuariosVista.php

require_once __DIR__ . '/../model/UsuarioReportesDAO.php'; 

// --- LÓGICA DE DATOS PARA GRÁFICO Y TABLA (SE MANTIENE IGUAL) ---
$dao = new UsuarioReportesDAO();
$usuarios = $dao->listarUsuariosConRol(); 
$fechaGeneracion = date('d/m/Y H:i');

$datos_grafico = $dao->contarUsuariosPorRol(); 
$labels = json_encode(array_column($datos_grafico, 'rol_nombre'));
$data = json_encode(array_column($datos_grafico, 'conteo'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vista Previa: Informe de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Paleta de Colores: Verde Salud (#4CAF50) y Azul Confianza (#2196F3) */
        :root {
            --color-principal: #1de7ddff; /* Verde Oscuro Vet */
            --color-secundario: #51f3ebff; /* Verde Medio */
            --color-fondo-tabla: #a7fffbff; /* Verde muy claro */
            --color-texto-header: #ffffff;
            --color-borde: #0259caff;
        }

        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f7f7f7; }
        
        /* Contenedor Principal (Tarjeta) */
        .report-card { 
            border: 1px solid var(--color-borde);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Encabezado del Reporte */
        .encabezado { 
            background-color: var(--color-principal); 
            color: var(--color-texto-header);
            padding: 20px 0;
            border-bottom: 5px solid var(--color-secundario);
            margin-bottom: 20px;
        }
        .encabezado h1, .encabezado p { color: var(--color-texto-header); }
        .encabezado p { font-size: 14px; opacity: 0.9; }

        /* Estilo de la Tabla */
        .data-table { 
            margin-top: 20px; 
            font-size: 14px; 
            border: 1px solid var(--color-borde) !important;
        }
        .data-table th { 
            background-color: var(--color-secundario); 
            color: var(--color-texto-header);
            border-color: var(--color-borde) !important;
            font-weight: bold;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: var(--color-fondo-tabla); /* Líneas alternadas */
        }
        .data-table td {
             border-color: #ccc !important;
        }

        /* Contenedor del Gráfico */
        .chart-container { 
            margin: 30px auto; 
            width: 70%; /* Más ancho */
            max-height: 450px; 
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* Estilo de Botones */
        .btn-toolbar .btn-danger { background-color: #ff4d4d; border-color: #ff4d4d; }
        .btn-toolbar .btn-success { background-color: #9b4cafff; border-color: #3820a5ff; }
        .btn-toolbar .btn-outline-info { color: var(--color-principal); border-color: var(--color-principal); }
        .btn-toolbar .btn-outline-info:hover { background-color: var(--color-principal); color: var(--color-texto-header); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="btn-toolbar justify-content-end">
            
            <div class="btn-group mr-3" role="group">
                <button class="btn btn-outline-primary" id="barBtn" onclick="toggleChartType('bar')">Gráfico de **Barra**</button>
                <button class="btn btn-outline-secondary" id="pieBtn" onclick="toggleChartType('pie')">Gráfico de **Pastel**</button>
            </div>
            
            <button class="btn btn-outline-info mr-3" id="toggleGraphButton">
                Ocultar Gráfica
            </button>
            
            <a href="../reportes/reporteUsuarios.php" target="_blank" class="btn btn-danger mr-2">
                Generar PDF Final 📄
            </a>
            <a href="../reportes/reporteUsuariosExcel.php" class="btn btn-success">
                Descargar Excel 📊
            </a>
        </div>
        
        <div class="card p-4 report-card">
            <div class="encabezado">
                <h1>🐾 Informe de Usuarios del Sistema Veterinario</h1>
                <p>Listado de cuentas activas y su distribución por roles.</p>
                <p>Generado: **<?= $fechaGeneracion ?>**</p>
            </div>
            
            <div class="chart-container" id="graphContainer">
                <h3 class="text-center mb-3 text-secondary">Distribución de Personal por Rol</h3>
                <canvas id="chartUsuarios"></canvas>
            </div>
            
            <h3 class="mt-4 text-secondary">Detalle Completo de Usuarios</h3>
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay usuarios registrados que coincidan con los filtros.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['idusuario'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['nombrecompleto'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['correoelectronico'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['nombreusuario'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['nombre_rol'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        const ctx = document.getElementById('chartUsuarios').getContext('2d');
        const chartLabels = <?php echo $labels; ?>;
        const chartData = <?php echo $data; ?>;
        let myChart;

        function createChart(type) {
            if (myChart) {
                myChart.destroy();
            }
            
            const dataConfig = {
                labels: chartLabels,
                datasets: [{
                    label: (type === 'bar' ? 'Cantidad de Usuarios' : 'Distribución'),
                    data: chartData,
                    // Colores de la gráfica ahora más suaves y profesionales
                    backgroundColor: [
                        '#cc2ed1ff', // Verde suave
                        '#430150ff', // Verde fuerte
                        '#3496cfff', // Verde claro
                        '#155da1ff', // Verde oscuro
                    ],
                    borderWidth: 1
                }]
            };

            const options = (type === 'bar') ? {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            } : {
                responsive: true,
                maintainAspectRatio: false,
            };
            
            myChart = new Chart(ctx, { type: type, data: dataConfig, options: options });
            
            // Actualiza los botones activos
            document.getElementById('barBtn').classList.remove('btn-primary', 'btn-secondary');
            document.getElementById('barBtn').classList.add('btn-outline-primary');
            document.getElementById('pieBtn').classList.remove('btn-primary', 'btn-secondary');
            document.getElementById('pieBtn').classList.add('btn-outline-secondary');

            if (type === 'bar') {
                document.getElementById('barBtn').classList.remove('btn-outline-primary');
                document.getElementById('barBtn').classList.add('btn-primary');
            } else {
                document.getElementById('pieBtn').classList.remove('btn-outline-secondary');
                document.getElementById('pieBtn').classList.add('btn-secondary');
            }
        }

        window.toggleChartType = function(type) {
            if (chartLabels.length > 0 && chartData.length > 0) {
                createChart(type);
            }
        };
        
        toggleChartType('bar'); 

        const button = document.getElementById('toggleGraphButton');
        const graphContainer = document.getElementById('graphContainer');

        button.addEventListener('click', () => {
            if (graphContainer.style.display === 'none') {
                graphContainer.style.display = 'block';
                button.textContent = 'Ocultar Gráfica';
                toggleChartType(myChart.config.type); 
            } else {
                graphContainer.style.display = 'none';
                button.textContent = 'Mostrar Gráfica';
            }
        });
    </script>
</body>
</html>