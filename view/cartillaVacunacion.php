<?php
require_once __DIR__ . "/../model/cartillaModel.php";
$model = new CartillaModel();
$cartillas = $model->obtenerCartillas();
$mascotas  = $model->obtenerMascotas();
$usuarios  = $model->obtenerUsuarios();
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartillas de Vacunación</title>
 
    <link rel="stylesheet" href="vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
 
    <style>
    body {
        background-color: #fefefe;
        font-family: 'Nunito', sans-serif;
    }
    .container {
        background-color: #ffffff;
        padding: 35px;
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        margin-top: 30px;
    }
    h2 {
        color: #4b4b4b;
        font-weight: 700;
        text-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    .btn {
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    .btn-primary { background-color: #5b9bd5; border-color: #5b9bd5; }
    .btn-primary:hover { background-color: #4a8ac2; border-color: #4a8ac2; }
    .btn-warning { background-color: #fbc658; border-color: #fbc658; }
    .btn-warning:hover { background-color: #e6a844; border-color: #e6a844; }
    .btn-danger { background-color: #eb5e6b; border-color: #eb5e6b; }
    .btn-danger:hover { background-color: #d94d5a; border-color: #d94d5a; }
    .btn-success { background-color: #51c160ff; border-color: #51c160ff; }
    table { border-radius: 12px; overflow: hidden; }
    thead { background-color: #f7f7f7; }
    thead th { text-align: center; font-weight: 600; color: #333; }
    tbody td { vertical-align: middle; text-align: center; color: #555; }
    tbody tr:nth-child(even) { background-color: #fafafa; }
    tbody tr:hover { background-color: #f1fdfc; transform: scale(1.01); transition: all 0.2s ease; }
    .modal-content { border-radius: 15px; border: 1px solid #e0e0e0; }
    .modal-header { border-bottom: none; }
    .modal-footer { border-top: none; }
    .form-control { border-radius: 10px; border: 1px solid #ccc; transition: all 0.2s ease; }
    .form-control:focus { border-color: #51c160ff; box-shadow: 0 0 5px rgba(126,199,83,0.4); }
    label { font-weight: 600; color: #4b4b4b; }
    </style>
</head>
<body>
 
<div class="container mt-5">
    <h2 class="text-center mb-4">Cartillas de Vacunación</h2>
    <button class="btn btn-primary mb-3" onclick="nuevaCartilla()">Nueva Cartilla</button>
 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Cartilla</th>
                <th>ID Mascota</th>
                <th>ID Usuario</th>
                <th>Fecha</th>
                <th>Peso</th>
                <th>Altura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaCartillas">
            <?php foreach ($cartillas as $c): ?>
            <tr>
                <td><?= $c['idcartillavacunacion'] ?></td>
                <td><?= $c['idmascota'] ?></td>
                <td><?= $c['idusuario'] ?></td>
                <td><?= $c['fecha'] ?></td>
                <td><?= $c['peso'] ?></td>
                <td><?= $c['altura'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm editar"
                        data-id="<?= $c['idcartillavacunacion'] ?>"
                        data-idmascota="<?= $c['idmascota'] ?>"
                        data-idusuario="<?= $c['idusuario'] ?>"
                        data-fecha="<?= $c['fecha'] ?>"
                        data-peso="<?= $c['peso'] ?>"
                        data-altura="<?= $c['altura'] ?>">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm eliminar"
                        data-id="<?= $c['idcartillavacunacion'] ?>">
                        Eliminar
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
 
<!-- MODAL -->
<div class="modal fade" id="modalNuevaCartilla" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
 
            <div class="modal-header" style="background-color:#51c160ff;">
                <h5 class="modal-title text-white" id="tituloModal">Nueva Cartilla</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
 
            <form id="frmCartilla">
                <div class="modal-body">
                    <input type="hidden" id="idcartillavacunacion" name="idcartillavacunacion">
 
                    <div class="form-group mb-2">
                        <label for="idmascota">Mascota ID</label>
                        <select id="idmascota" name="idmascota" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($mascotas as $m): ?>
                                <option value="<?= $m['idmascota'] ?>"><?= $m['idmascota'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
 
                    <div class="form-group mb-2">
                        <label for="idusuario">Usuario ID</label>
                        <select id="idusuario" name="idusuario" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= $u['idusuario'] ?>"><?= $u['idusuario'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
 
                    <div class="form-group mb-2">
                        <label>Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                    </div>
 
                    <div class="form-group mb-2">
                        <label>Peso</label>
                        <input type="number" step="0.01" id="peso" name="peso" class="form-control" required>
                    </div>
 
                    <div class="form-group mb-2">
                        <label>Altura</label>
                        <input type="number" step="0.01" id="altura" name="altura" class="form-control" required>
                    </div>
 
                </div>
 
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                </div>
 
            </form>
 
        </div>
    </div>
</div>
 
<!-- SCRIPTS: jQuery primero, Bootstrap, JS -->
<script src="vendor/jquery3.7.1/jquery.min.js"></script>
<script src="vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="js/cartilla.js"></script>
 
<script>
 
</script>
 
</body>
</html>