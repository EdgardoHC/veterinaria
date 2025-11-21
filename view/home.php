<?php
// Aseguramos la existencia de la variable $usuario para la bienvenida y protección de rutas.
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

    <?php include 'view/componets/navbar.php'; ?>

    <main class="container mt-5">

        <div class="jumbotron text-center">
            <h1 class="display-4">¡Bienvenido de nuevo!</h1>
            <p class="lead">Sistema de Gestión Veterinaria. Desde aquí puedes acceder a todas las funciones principales.</p>
            <hr class="my-4">
            <p>Selecciona una de las siguientes opciones para comenzar a trabajar.</p>
        </div>

        <h2 class="text-center mb-4">Menú Principal</h2>
        <div class="row">

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
            
             <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] === "Administrador") { ?>
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
            <?php }?>
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

            <div class="col-12 mt-4 text-center">
                <h3>Catálogos de Sistema</h3>
                <p>
                    <a href="index.php?page=raza" class="btn btn-outline-secondary m-1">Raza</a>
                    <a href="index.php?page=padecimientos" class="btn btn-outline-secondary m-1">Padecimiento</a>
                </p>
            </div>
        </div>
    </main>
    
    <footer class="text-center text-muted mt-5 mb-4">
        <p>&copy; <?php echo date("Y"); ?> Veterinaria. Todos los derechos reservados.</p>
    </footer>


    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>