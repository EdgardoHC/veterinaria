<?php
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Veterinaria</title>
    
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa; 
        }
        .card-icon {
            font-size: 3rem;
            color: #007bff;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .jumbotron {
            border-radius: 15px;
        }
    </style>
</head>
<body>

    <!-- ======== BARRA DE NAVEGACIÓN ======== -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <a class="navbar-brand" href="#">
            <i class="fas fa-paw"></i>
            Veterinaria
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?page=home">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=usuarios">Gestión de Usuarios</a>
                </li>
            </ul>
            <span class="navbar-text text-white mr-3">
                ¡Hola, <?php echo htmlspecialchars($usuario['nombre']); ?>!
            </span>
            <a href="index.php?page=logout" class="btn btn-outline-light">Cerrar Sesión</a>
        </div>
    </nav>

    <!-- ======== CONTENIDO PRINCIPAL ======== -->
    <main class="container mt-5">

        <!-- --- Jumbotron de Bienvenida --- -->
        <div class="jumbotron text-center">
            <h1 class="display-4">¡Bienvenido de nuevo!</h1>
            <p class="lead">Sistema de Gestión Veterinaria. Desde aquí puedes acceder a todas las funciones principales.</p>
            <hr class="my-4">
            <p>Selecciona una de las siguientes opciones para comenzar a trabajar.</p>
        </div>

        <!-- --- Tarjetas de Acceso Rápido (de ejemplos)--- -->
        <h2 class="text-center mb-4">Menú Principal</h2>
        <div class="row">

            <!-- Tarjeta 1: Gestionar Mascotas -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-dog"></i>
                        </div>
                        <h5 class="card-title">Gestionar Mascotas</h5>
                        <p class="card-text">Registra nuevas mascotas, consulta su historial y actualiza sus datos.</p>
                        <a href="#" class="btn btn-primary">Ir ahora</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Gestión de Usuarios -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h5 class="card-title">Gestión de Usuarios</h5>
                        <p class="card-text">Administra los usuarios del sistema, sus roles y permisos de acceso.</p>
                        <a href="index.php?page=usuarios" class="btn btn-primary">Ir ahora</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Agenda de Citas -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h5 class="card-title">Agenda de Citas</h5>
                        <p class="card-text">Programa nuevas citas, consulta las próximas y gestiona el calendario.</p>
                        <a href="#" class="btn btn-primary">Ir ahora</a>
                    </div>
                </div>
            </div>


        </div>
    </main>
    
    <!-- ======== FOOTER ======== -->
    <footer class="text-center text-muted mt-5 mb-4">
        <p>&copy; <?php echo date("Y"); ?> Veterinaria. Todos los derechos reservados.</p>
    </footer>


    <!-- Scripts de JavaScript -->
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>