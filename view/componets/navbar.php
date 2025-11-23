<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['usuario'];

// Detecta la página actual desde el parámetro "page"
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <a class="navbar-brand" href="#">
        <i class="fas fa-paw"></i>
        Veterinaria
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo ($page === 'home') ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php?page=home">Inicio</a>
            </li>

            <li class="nav-item dropdown <?php echo ($page === 'cartillas' || $page === 'recetas') ? 'active' : ''; ?>">
                <a class="nav-link dropdown-toggle text-white" href="#" id="procesosDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Procesos
                </a>
                <div class="dropdown-menu" aria-labelledby="procesosDropdown">
                    <a class="dropdown-item" href="view/cartillaVacunacion.php">Ingreso de cartillas</a>
                    <a class="dropdown-item" href="view/recetas.php">Ingreso de recetas</a>
                </div>
            </li>

            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']["rol_nombre"] === "Administrador") { ?>
                <li class="nav-item <?php echo ($page === 'usuarios') ? 'active' : ''; ?>">
                    <a class="nav-link" href="index.php?page=usuarios">Gestión de Usuarios</a>
                </li>
            <?php } ?>
        </ul>

        <span class="navbar-text text-white mr-3">
            ¡Hola, <?php echo htmlspecialchars($usuario['nombre']); ?>!
        </span>
        <a href="index.php?page=logout" class="btn btn-outline-light">Cerrar Sesión</a>
    </div>
</nav>

