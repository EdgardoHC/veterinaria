<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacunas - Veterinaria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <?php include 'components/navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-syringe text-success me-2"></i>Vacunas</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="fas fa-plus"></i> Nueva Vacuna
            </button>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Fabricante</th>
                            <th>Dosis</th>
                            <th>Frecuencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos simulados -->
                        <tr>
                            <td>1</td>
                            <td>Antirrábica</td>
                            <td>Pfizer Vet</td>
                            <td>1 ml</td>
                            <td>Anual</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Vacuna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fabricante</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosis</label>
                            <input type="text" class="form-control" placeholder="Ej: 1 ml">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Frecuencia</label>
                            <input type="text" class="form-control" placeholder="Ej: Anual, Semestral">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Editar Vacuna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="Antirrábica">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fabricante</label>
                            <input type="text" class="form-control" value="Pfizer Vet">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosis</label>
                            <input type="text" class="form-control" value="1 ml">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Frecuencia</label>
                            <input type="text" class="form-control" value="Anual">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-warning text-white">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
