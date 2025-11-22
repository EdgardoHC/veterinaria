<?php
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso de Recetas</title>
 
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
 
<body class="bg-light">
 
<div class="container py-4">
 
    <h2 class="text-center mb-4">Ingreso de Recetas</h2>
 
    <button class="btn btn-primary mb-3" onclick="abrirModalNuevaReceta()">
        Nueva Receta
    </button>
 
    <table class="table table-bordered table-hover" id="tablaRecetas">
        <thead class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>ID Expediente</th>
                <th>Dosis</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
 
 
<!-- Modal -->
<div class="modal fade" id="modalReceta" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
 
            <div class="modal-header text-white" style="background-color:#51c160ff;">
                <h5 class="modal-title">Receta</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
 
            <div class="modal-body">
                <input type="hidden" id="idreceta">
 
                <div class="mb-3">
                    <label>Fecha</label>
                    <input type="datetime-local" class="form-control" id="fecha">
                </div>
 
                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea class="form-control" id="descripcion"></textarea>
                </div>
 
                <div class="mb-3">
                    <label>ID Expediente</label>
                    <select class="form-control" id="idexpedientedetalle"></select>
                </div>
 
                <div class="mb-3">
                    <label>Dosis</label>
                    <input type="text" class="form-control" id="dosis">
                </div>
            </div>
 
            <div class="modal-footer">
                <button class="btn btn-success" onclick="guardarReceta()">Guardar</button>
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
 
        </div>
    </div>
</div>
 
<!-- SCRIPTS: jQuery, Bootstrap -->
<script src="vendor/jquery3.7.1/jquery.min.js"></script>
<script src="vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="js/recetas.js"></script>
 
</body>
</html>
 