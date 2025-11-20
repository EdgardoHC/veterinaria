<?php
// Navbar component --jadrianh
?>
<nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm fixed-top modern-navbar">
    <div class="container-fluid px-4">
        <!-- Logo y Nombre -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="dashboard.php">
            <img src="/veterinaria/assets/icon.png" alt="logo" width="56" height="56" class="d-inline-block align-top logo-img">
            <span class="site-title ms-2">Veterinaria</span>
        </a>

        <!-- Móvil Boton Alternar -->
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Elementos de navegación -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-lg-2 gap-1">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-anim" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administración General
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="/Encargado">Encargado</a></li>
                        <li><a class="dropdown-item" href="/AreaTrabajo">Área de trabajo</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-anim" href="#" id="saludDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Salud y Veterinaria
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="saludDropdown">
                        <li><a class="dropdown-item" href="/veterinaria/view/padecimientos.php">Padecimientos</a></li>
                        <li><a class="dropdown-item" href="/veterinaria/view/vacunas.php">Vacunas</a></li>
                        <li><a class="dropdown-item" href="/veterinaria/view/tipo_control_medico.php">Tipo control médico</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-anim" href="#" id="clasifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Clasificación/Tipos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="clasifDropdown">
                        <li><a class="dropdown-item" href="/Raza">Raza</a></li>
                        <li><a class="dropdown-item" href="/Clase">Clase</a></li>
                    </ul>
                </li>
                <!-- Botones de autenticación -->
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a class="btn btn-login px-4 py-2 me-2" href="/login">Login</a>
                </li>
                <li class="nav-item mt-2 mt-lg-0">
                    <a class="btn btn-signup px-4 py-2 me-2" href="/signup">Sign Up</a>
                </li>
                <li class="nav-item mt-2 mt-lg-0">
                    <a class="btn btn-logout px-4 py-2" href="/logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="margin-top: 90px;"></div>

<!-- Custom CSS -->
<style>
.modern-navbar {
    border-radius: 0 0 1.5rem 1.5rem;
    box-shadow: 0 4px 24px rgba(0, 123, 255, 0.07), 0 1.5px 4px rgba(0,0,0,0.03);
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    background: linear-gradient(90deg, #f8fafc 0%, #e3f0ff 100%);
}
.logo-img {
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,123,255,0.10);
    background: #fff;
    border: 2px solid #e3f0ff;
}
.site-title {
    font-size: 2rem;
    font-weight: 700;
    color: #81b64c;
    letter-spacing: 1px;
    font-family: 'Segoe UI', 'Arial', sans-serif;
    text-shadow: 0 1px 0 #fff, 0 2px 8px rgba(0,123,255,0.08);
}
.nav-link {
    font-size: 1.1rem;
    font-weight: 500;
    color: #333 !important;
    padding: 0.5rem 1.1rem;
    border-radius: 0.7rem;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    position: relative;
}
.nav-link.nav-anim::after {
    display: block;
    width: 0;
    height: 2px;
    background: #111111;
    transition: width 0.3s;
    position: absolute;
    left: 2%;
    bottom: 3px;
}
.nav-link.nav-anim:hover::after {
    background: #007bff;
    width: 90%;
}
.nav-link:hover, .nav-link.active {
    background: #e3f0ff;
    color: #007bff !important;
    box-shadow: 0 2px 8px rgba(0,123,255,0.07);
}
.dropdown-menu {
  display: block;
  opacity: 0;
  transform: translateY(12px) scale(0.98);
  visibility: hidden;
  transition: all 0.50s cubic-bezier(0.16, 1, 0.3, 1);
  border-radius: 0.75rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.dropdown-menu.showing,
.dropdown-menu.show {
  opacity: 1;
  transform: translateY(0) scale(1);
  visibility: visible;
}
.dropdown-menu.hiding {
  opacity: 0;
  transform: translateY(12px) scale(0.98);
  visibility: hidden;
}
.dropdown-item {
    font-size: 1rem;
    color: #555 !important;
    padding: 0.5rem 1.5rem;
    transition: background 0.2s, color 0.2s, font-size 0.2s;
}
.dropdown-item:hover {
    font-size: 1.2rem;
    color: #007bff !important;
}
.btn-login {
    background: #d9f9ffff;
    color: #007bff;
    border: 2px solid #007bff;
    border-radius: 2rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,123,255,0.05);
    transition: all 0.2s;
}
.btn-login:hover {
    background: #007bff;
    color: #fff;
    box-shadow: 0 4px 16px rgba(0,123,255,0.13);
    transform: translateY(-2px) scale(1.04);
}
.btn-signup {
    background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
    color: #fff;
    border: none;
    border-radius: 2rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,123,255,0.10);
    transition: all 0.2s;
}
.btn-signup:hover {
    background: linear-gradient(90deg, #0056b3 0%, #00aaff 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(0,123,255,0.18);
    transform: translateY(-2px) scale(1.04);
}
.btn-logout {
    background: #fff0f0;
    color: #dc3545;
    border: 2px solid #dc3545;
    border-radius: 2rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(220,53,69,0.07);
    transition: all 0.2s;
}
.btn-logout:hover {
    background: #dc3545;
    color: #fff;
    box-shadow: 0 4px 16px rgba(220,53,69,0.13);
    transform: translateY(-2px) scale(1.04);
}
.navbar-toggler.custom-toggler {
    border: none;
    background: #e3f0ff;
    border-radius: 50%;
    padding: 0.5rem 0.7rem;
    box-shadow: 0 2px 8px rgba(0,123,255,0.07);
    transition: background 0.2s;
}
.navbar-toggler.custom-toggler:focus {
    outline: none;
    background: #cce3ff;
}
@media (max-width: 991.98px) {
    .modern-navbar {
        border-radius: 0 0 1rem 1rem;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .navbar-nav {
        background: #f8fafc;
        border-radius: 1rem;
        margin-top: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,123,255,0.04);
        padding: 1rem 0.5rem;
    }
    .nav-item {
        margin: 0.4rem 0;
    }
    .btn-login, .btn-signup, .btn-logout {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    .site-title {
        font-size: 1.3rem;
    }
}
</style>