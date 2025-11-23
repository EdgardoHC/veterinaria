<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../model/MascotaDAO.php";
try {
    $mascotaDAO = new MascotaDAO();
    $listaMascotas = $mascotaDAO->listarMascotas() ?: [];

    $nombreVeterinarioLogueado = $_SESSION['usuario']['nombre'] ?? 'Usuario Desconocido';

} catch (Exception $e) {
    $errorBD = "Error de conexión: " . $e->getMessage();
    $listaMascotas = [];
    $nombreVeterinarioLogueado = "Error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Expediente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .required:after { content: " *"; color: red; }
        body { background-color: #f8f9fa; }
        .input-readonly {
            background-color: #e9ecef;
            cursor: not-allowed;
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <?php if (isset($errorBD)): ?>
        <div class="alert alert-danger"><?= $errorBD ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-medical"></i> Nuevo Expediente Médico</h5>
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
                    <label class="form-label required">Descripción / Motivo</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3" placeholder="Ingrese el motivo de la consulta..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Veterinario Responsable</label>
                    <input type="text" 
                           class="form-control input-readonly" 
                           value="<?= htmlspecialchars($nombreVeterinarioLogueado) ?>" 
                           readonly 
                           title="Este campo se llena automáticamente con su usuario">
                </div>

                <div id="msgResult"></div>
                <div class="d-flex justify-content-end gap-2">
                     <button type="submit" id="btnGuardar" class="btn btn-success"><i class="fas fa-save"></i> Guardar Expediente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../view/js/expediente.js"></script> 

</body>
</html>