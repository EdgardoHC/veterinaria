<?php
$usuario = $_SESSION['usuario']; // esta es la sesión parte del trabajo del equipo #1.
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Veterinaria</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { background-color: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #5a8dee 100%); }
        .hero .card { background: rgba(255, 255, 255, .08); backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,.2); }
        .service-icon { font-size: 2.2rem; color: #0d6efd; }
        .service-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(13,110,253,.08); transition: transform .18s ease, box-shadow .18s ease; }
        .service-card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px rgba(13,110,253,.12); }
        .urgency-banner a { color: #fff; text-decoration: none; }
        .testimonial-card { border: none; border-radius: 16px; box-shadow: 0 10px 24px rgba(0,0,0,.06); }
    </style>
</head>

<body>
    <?php include 'view/components/navbar.php'; ?>

    <main>
        <section class="hero py-5 text-white">
            <div class="container py-4">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="display-5 fw-semibold mb-3">Cuidado integral para tus mascotas</h1>
                        <p class="lead mb-4">Atención profesional, registro clínico y seguimiento de salud, todo en un solo lugar.</p>
                        <a href="index.php?page=ingresarConsulta" class="btn btn-light btn-lg me-2"><i class="fa-solid fa-calendar-check me-2"></i>Agendar Cita</a>
                    </div>
                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="card p-4">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-shield-dog fa-3x me-3 text-white"></i>
                                <div>
                                    <h5 class="mb-1 text-white">Confianza y seguridad</h5>
                                    <p class="mb-0 text-white">Equipo experto y procesos auditados para cuidar lo que más quieres.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container py-5">
            <h2 class="text-center mb-4">Servicios Destacados</h2>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <div class="service-icon mb-3"><i class="fa-solid fa-syringe"></i></div>
                            <h5 class="card-title">Vacunación</h5>
                            <p class="card-text">Calendarios al día y control de inmunización para una vida saludable.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <div class="service-icon mb-3"><i class="fa-solid fa-stethoscope"></i></div>
                            <h5 class="card-title">Consultas</h5>
                            <p class="card-text">Evaluación clínica y seguimiento con historial detallado de tu mascota.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <div class="service-icon mb-3"><i class="fa-solid fa-kit-medical"></i></div>
                            <h5 class="card-title">Urgencias</h5>
                            <p class="card-text">Atención prioritaria en casos críticos con personal capacitado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="urgency-banner bg-danger text-white py-3">
            <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between">
                <p class="h5 mb-2 mb-lg-0"><i class="fa-solid fa-triangle-exclamation me-2"></i>Urgencias 24/7</p>
                <p class="mb-0">Llámanos al <a href="#">(503) 2222-0000</a></p>
            </div>
        </section>
    </main>

    <footer class="text-center text-muted mt-5 mb-4">
        <p>&copy; <?php echo date("Y"); ?> Veterinaria. Todos los derechos reservados.</p>
    </footer>

    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>