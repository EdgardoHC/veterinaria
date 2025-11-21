<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['usuario'];
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <i class="fas fa-paw"></i>
            Veterinaria
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item <?php echo ($page === 'home') ? 'active' : ''; ?>">
                    <a class="nav-link" href="index.php?page=home">Inicio</a>
                </li>
                <?php // if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] === "Administrador") { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menu1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Gestión
                    </a>
                    <ul class="dropdown-menu animated-dropdown" aria-labelledby="menu1">
                        <li><a class="dropdown-item" href="index.php?page=usuarios">Gestión de Usuarios</a></li>
                        <li><a class="dropdown-item" href="index.php?page=encargado">Encargado</a></li>
                    </ul>
                </li>
                <?php // } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menu2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Salud y Veterinaria
                    </a>
                    <ul class="dropdown-menu animated-dropdown" aria-labelledby="menu2">
                        <li><a class="dropdown-item" href="index.php?page=padecimientos">Padecimientos</a></li>
                        <li><a class="dropdown-item" href="index.php?page=vacunas">Vacunación</a></li>
                        <li><a class="dropdown-item" href="index.php?page=tipoControlMedico">Tipo control médico</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menu3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Clasificación/Tipos
                    </a>
                    <ul class="dropdown-menu animated-dropdown" aria-labelledby="menu3">
                        <li><a class="dropdown-item" href="index.php?page=raza">Raza</a></li>
                        <li><a class="dropdown-item" href="#">Clase</a></li>
                    </ul>
                </li>
            </ul>

            <span class="navbar-text text-white me-3">
                ¡Hola, <?php echo htmlspecialchars($usuario['nombre']); ?>!
            </span>
            <a href="index.php?page=logout" class="btn btn-outline-light">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<style>
.animated-dropdown {
    transition: transform 0.25s ease, opacity 0.25s ease;
    transform: translateY(10px);
    opacity: 0;
    pointer-events: none;
    display: block; /* Permite animación de transición */
}
.show > .animated-dropdown,
.dropdown.show .animated-dropdown {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
}
.navbar-nav .nav-link {
    transition: color 0.2s;
    position: relative;
}
.navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
    color: #ffe082 !important;
}
.dropdown-item:hover, .dropdown-item:focus {
    background: #e3f1fc;
    color: #1565c0;
}
</style>

<!-- JavaScript para animar hover -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth > 992) {
        document.querySelectorAll('.navbar .dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('mouseenter', function() {
                let menu = this.querySelector('.dropdown-menu');
                this.classList.add('show');
                menu.classList.add('show');
            });
            dropdown.addEventListener('mouseleave', function() {
                let menu = this.querySelector('.dropdown-menu');
                this.classList.remove('show');
                menu.classList.remove('show');
            });
        });
    }
});
</script>
