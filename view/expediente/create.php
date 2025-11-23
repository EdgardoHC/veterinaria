<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../model/MascotaDAO.php";
require_once __DIR__ . "/../../model/UsuarioDAO.php";

try {
    $mascotaDAO = new MascotaDAO();
    $usuarioDAO = new UsuarioDAO();

    $listaMascotas = $mascotaDAO->listarMascotas() ?: [];
    $listaVeterinarios = $usuarioDAO->listarVeterinarios() ?: [];
} catch (Exception $e) {
    $errorBD = "Error de conexión: " . $e->getMessage();
    $listaMascotas = [];
    $listaVeterinarios = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Expediente (Modo Test)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .required:after { content: " *"; color: red; }
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

<div class="container mt-5">

    <?php if (isset($errorBD)): ?>
        <div class="alert alert-danger"><?= $errorBD ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Nuevo Expediente Médico</h5>
        </div>
        <div class="card-body">
            <form id="formExpediente">
                
                <div class="mb-3">
                    <label class="form-label required">Mascota</label>
                    <select id="mascota_id" name="mascota_id" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($listaMascotas as $m): 
                            $id = $m['idmascota'] ?? $m['idMascota'] ?? $m['id'];
                            $nom = $m['nombre'] ?? $m['nombres'];
                        ?>
                            <option value="<?= $id ?>"><?= $nom ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Veterinario Responsable</label>
                    <select id="veterinario_id" name="veterinario_id" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($listaVeterinarios as $v): 
                            $id = $v['idusuario'] ?? $v['idUsuario'] ?? $v['id'];
                            $nom = $v['nombre'] . ' ' . ($v['apellidos'] ?? '');
                        ?>
                            <option value="<?= $id ?>"><?= $nom ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="msgResult"></div>
                <div class="d-flex justify-content-end">
                    <button type="submit" id="btnGuardar" class="btn btn-success">Guardar Expediente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Poner fecha actual
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    $('#fecha').val(now.toISOString().slice(0, 16));

    $('#formExpediente').on('submit', function(e) {
        e.preventDefault();
        $('#btnGuardar').prop('disabled', true).text('Guardando...');
        
        var formData = new FormData(this);

        $.ajax({
            url: '../../controller/ExpedienteController.php?op=store',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(res) {
                $('#btnGuardar').prop('disabled', false).text('Guardar Expediente');
                if (res.success) {
                    $('#msgResult').html('<div class="alert alert-success">¡Guardado! ID: ' + res.id + '</div>');
                    $('#formExpediente')[0].reset();
                    $('#fecha').val(now.toISOString().slice(0, 16));
                } else {
                    $('#msgResult').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            },
            error: function(xhr) {
                $('#btnGuardar').prop('disabled', false).text('Guardar Expediente');
                console.log(xhr.responseText);
                $('#msgResult').html('<div class="alert alert-danger">Error de servidor. Revisa la consola.</div>');
            }
        });
    });
});
</script>
</body>
</html>