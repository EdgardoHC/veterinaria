<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razas - Veterinaria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="view/js/Raza.js"></script>
</head>

<body class="bg-light">
    <?php include 'components/navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-heartbeat text-danger me-2"></i>Razas</h2>
            <button class="btn btn-primary" onclick="nuevaRaza()">
                <i class="fas fa-plus"></i> Nuevo Raza
            </button>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <table class="table table-hover align-middle" id="tablaRazas">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Aciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="modalNuevaRaza" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalRaza">Nueva Raza</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmRaza" method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" id="idRaza" name="idRaza">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nombre de raza</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="btnGuardarRaza" type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>