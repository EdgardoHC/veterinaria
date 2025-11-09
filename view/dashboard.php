<!-- Dashboard actualizado, puede ser que no sea necesario cambiar el dashboard original pero por si acaso deje el original con _old -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Veterinaria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-light">
    <?php include 'components/navbar.php'; ?>
    
    <div class="container py-4">
        <!-- Seccion Bienvenida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 welcome-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="welcome-icon">
                                <i class="fas fa-paw"></i>
                            </div>
                            <div>
                                <h1 class="mb-0">Bienvenido al Panel de Control</h1>
                                <p class="text-muted mb-0">Sistema de Gestión Veterinaria</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seccion Acciones rapidas -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3">Acciones Rápidas</h5>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card action-card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="action-icon bg-primary-subtle">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                        </div>
                        <h5>Nueva Cita</h5>
                        <p class="text-muted">Programa una nueva cita veterinaria</p>
                        <a href="/nueva-cita" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card action-card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="action-icon bg-success-subtle">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <h5>Buscar Paciente</h5>
                        <p class="text-muted">Consulta el historial de pacientes</p>
                        <a href="/buscar-paciente" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card action-card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="action-icon bg-info-subtle">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                        <h5>Registros Médicos</h5>
                        <p class="text-muted">Accede a los registros médicos</p>
                        <a href="/registros-medicos" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seccion de estadisticas -->
        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">Resumen del Día</h5>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-0">Citas Hoy</p>
                                <h3 class="mb-0">12</h3>
                            </div>
                            <div class="stat-icon bg-primary-subtle">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-0">Pacientes</p>
                                <h3 class="mb-0">5</h3>
                            </div>
                            <div class="stat-icon bg-success-subtle">
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-0">Pendientes</p>
                                <h3 class="mb-0">3</h3>
                            </div>
                            <div class="stat-icon bg-warning-subtle">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-0">Completadas</p>
                                <h3 class="mb-0">7</h3>
                            </div>
                            <div class="stat-icon bg-info-subtle">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .welcome-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: transform 0.2s;
        }
        .welcome-card:hover {
            transform: translateY(-2px);
        }
        .welcome-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-icon i {
            font-size: 28px;
            color: white;
        }
        .action-card {
            transition: all 0.2s;
            cursor: pointer;
        }
        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,123,255,0.12) !important;
        }
        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .action-icon i {
            font-size: 20px;
            color: #007bff;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-icon i {
            font-size: 18px;
        }
        .bg-primary-subtle .stat-icon i { color: #0d6efd; }
        .bg-success-subtle .stat-icon i { color: #198754; }
        .bg-warning-subtle .stat-icon i { color: #ffc107; }
        .bg-info-subtle .stat-icon i { color: #0dcaf0; }
    </style>

    <!-- Custom Script -->
    <script>
        $(document).ready(function() {
            // Animacion para las tarjetas al cargar la pagina (Puede haber un error en navegador firefox -jadrianh)
            $('.card').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).delay(100 * index).animate({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                }, 500);
            });
        });
    </script>
</body>
</html>