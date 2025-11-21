<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encargados - Veterinaria</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="view/js/Encargado.js"></script>
</head>

<body class="bg-light">
    <?php include 'components/navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-tie text-success me-2"></i>Encargados</h2>
            <button class="btn btn-success" onclick="nuevoEncargado()">
                <i class="fas fa-plus"></i> Nuevo Encargado
            </button>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <table class="table table-hover align-middle" id="tablaEncargados">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Teléfono móvil</th>
                            <th>Correo electrónico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Agregar / Editar Encargado -->
    <div class="modal fade" id="modalEncargado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="tituloModalEncargado">Nuevo Encargado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="frmEncargado" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="modal-body">
                        <input type="hidden" id="idencargado" name="idencargado">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono fijo</label>
                                <input type="text" class="form-control" id="telefonofijo" name="telefonofijo">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono móvil</label>
                                <input type="text" class="form-control" id="telefonomovil" name="telefonomovil" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="correoelectronico" name="correoelectronico" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fechanacimiento" name="fechanacimiento">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sexo</label>
                                <select class="form-select" id="sexo" name="sexo">
                                    <option value="">Seleccione...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success" id="btnGuardarEncargado" type="submit">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>