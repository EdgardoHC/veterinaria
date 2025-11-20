<?php
require_once __DIR__ . "/../../model/MascotaDAO.php";
require_once __DIR__ . "/../../model/UsuarioDAO.php";

$mascotaDAO = new MascotaDAO();
$usuarioDAO = new UsuarioDAO();

$listaMascotas = $mascotaDAO->listarMascotas();
$listaVeterinarios = $usuarioDAO->listarVeterinarios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Expediente - Veterinaria</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .card-header { font-weight: bold; }
        .required:after { content: " *"; color: red; }
        .form-section { margin-bottom: 1rem; }
        #msgResult .alert { margin-bottom: 0; }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-folder-plus"></i> Crear Expediente Médico</h5>
                </div>
                <div class="card-body">
                    <form id="formExpediente" autocomplete="off" novalidate>
                        <!-- SELECT MASCOTAS -->
                        <div class="form-section">
                            <label for="mascota_id" class="form-label required">Mascota</label>
                            <select id="mascota_id" name="mascota_id" class="form-control" required>
                                <option value="">Seleccione una mascota</option>
                                <?php if (!empty($listaMascotas)): ?>
                                    <?php foreach ($listaMascotas as $m): ?>
                                        <option value="<?= htmlspecialchars($m['idMascota']); ?>">
                                            <?= htmlspecialchars($m['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay mascotas registradas</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- FECHA -->
                        <div class="form-section">
                            <label for="fecha" class="form-label required">Fecha</label>
                            <input type="date" id="fecha" name="fecha" class="form-control" required>
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="form-section">
                            <label for="descripcion" class="form-label required">Descripción (síntomas / motivo)</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" maxlength="500" rows="4" required></textarea>
                            <div class="form-text">Máx. 500 caracteres.</div>
                        </div>

                        <!-- SELECT VETERINARIOS -->
                        <div class="form-section">
                            <label for="veterinario_id" class="form-label required">Veterinario responsable</label>
                            <select id="veterinario_id" name="veterinario_id" class="form-control" required>
                                <option value="">Seleccione un veterinario</option>
                                <?php if (!empty($listaVeterinarios)): ?>
                                    <?php foreach ($listaVeterinarios as $v): ?>
                                        <option value="<?= htmlspecialchars($v['idUsuario']); ?>">
                                            <?= htmlspecialchars(trim($v['nombre'] . ' ' . ($v['apellidos'] ?? ''))); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay veterinarios</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div id="msgResult" style="flex:1"></div>
                            <div class="ms-3">
                                <button type="button" id="btnCancelar" class="btn btn-secondary">Cancelar</button>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar Expediente</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-muted small">
                    El expediente quedara asociado a la mascota seleccionada y al veterinario responsable.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery + Bootstrap JS (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/veterinaria/view/js/expediente.js?v=1"></script>

</body>
</html>
