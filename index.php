<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Reportes de Veterinaria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style> .container { margin-top: 50px; } .card { margin-bottom: 20px; } </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-5 text-primary text-center">🐾 Dashboard de Reportes Clínicos y Administrativos</h1>
        <div class="row">
        
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">👥 Reporte de Usuarios (R1)</h5>
                        <p class="card-text">Distribución de personal activo por roles.</p>
                        <a href="view/reporteUsuariosVista.php" target="_blank" class="btn btn-primary">Ver Vista Previa</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">🐶 Reporte de Pacientes (R2)</h5>
                        <p class="card-text">Listado de mascotas, dueños y distribución por raza.</p>
                        <a href="view/reporteMascotasVista.php" target="_blank" class="btn btn-info">Ver Vista Previa</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">🗓️ Próximas Citas (R4)</h5>
                        <p class="card-text">Vacunas y refuerzos pendientes de aplicación.</p>
                        <a href="view/reporteCitasVista.php" target="_blank" class="btn btn-warning">Ver Vista Previa</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">🩺 Detalle de Expedientes (R6)</h5>
                        <p class="card-text">Historial de consultas, diagnósticos y parámetros físicos.</p>
                        <a href="view/reporteExpedientesVista.php" target="_blank" class="btn btn-success">Ver Vista Previa</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">🔬 Padecimientos Comunes (R8)</h5>
                        <p class="card-text">Análisis gráfico de las enfermedades más diagnosticadas.</p>
                        <a href="view/reportePadecimientosVista.php" target="_blank" class="btn btn-danger">Ver Vista Previa</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">🏆 Clientes Frecuentes (R7)</h5>
                        <p class="card-text">Ranking de las mascotas con mayor número de visitas.</p>
                        <a href="view/reporteClientesFrecuentesVista.php" target="_blank" class="btn btn-info">Ver Vista Previa</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">👨‍💼 Personal y RRHH (R10)</h5>
                        <p class="card-text">Listado detallado de empleados, puestos y áreas de trabajo.</p>
                        <a href="view/reporteRRHHVista.php" target="_blank" class="btn btn-secondary">Ver Vista Previa</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>