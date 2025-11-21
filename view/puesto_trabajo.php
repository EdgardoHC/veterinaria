<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) { header("Location: index.php?page=login"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Puestos</title>
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <?php include 'view/componets/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Gestión de Puestos de Trabajo</h2>
        <div class="text-right mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalPuesto" onclick="limpiarFormulario()">
                <i class="fas fa-plus"></i> Nuevo Puesto
            </button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Puesto</th>
                    <th>Área Asignada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaPuestos"></tbody>
        </table>
    </div>

    <div class="modal fade" id="modalPuesto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Datos del Puesto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formPuesto">
                        <input type="hidden" id="idPuestoTrabajo" name="idPuestoTrabajo">
                        <input type="hidden" id="accion" name="accion" value="guardar">
                        
                        <div class="form-group">
                            <label>Nombre del Puesto:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Área de Trabajo:</label>
                            <select class="form-control" id="idAreaTrabajo" name="idAreaTrabajo" required>
                                <option value="">Seleccione un área...</option>
                                </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            listarPuestos();
            cargarSelectAreas(); // Llenamos el combo
        });

        function cargarSelectAreas() {
            // Usamos el controlador de Áreas para llenar el select
            $.post('controller/AreaTrabajoController.php', {accion: 'listar'}, function(data) {
                let opciones = '<option value="">Seleccione un área...</option>';
                data.forEach(area => {
                    opciones += `<option value="${area.idAreaTrabajo}">${area.nombre}</option>`;
                });
                $('#idAreaTrabajo').html(opciones);
            }, 'json');
        }

        function listarPuestos() {
            $.post('controller/PuestoTrabajoController.php', {accion: 'listar'}, function(data) {
                let html = '';
                data.forEach(puesto => {
                    html += `<tr>
                        <td>${puesto.idPuestoTrabajo}</td>
                        <td>${puesto.nombre}</td>
                        <td><span class="badge badge-info">${puesto.nombreArea}</span></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editar(${puesto.idPuestoTrabajo}, '${puesto.nombre}', ${puesto.idAreaTrabajo})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="eliminar(${puesto.idPuestoTrabajo})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
                $('#tablaPuestos').html(html);
            }, 'json');
        }

        $('#formPuesto').submit(function(e) {
            e.preventDefault();
            $.post('controller/PuestoTrabajoController.php', $(this).serialize(), function(res) {
                if(res.estatus) {
                    swal("Éxito", res.mensaje, "success");
                    $('#modalPuesto').modal('hide');
                    listarPuestos();
                } else {
                    swal("Error", res.mensaje, "error");
                }
            }, 'json');
        });

        function editar(id, nombre, idArea) {
            $('#idPuestoTrabajo').val(id);
            $('#nombre').val(nombre);
            $('#idAreaTrabajo').val(idArea);
            $('#accion').val('actualizar');
            $('#modalPuesto').modal('show');
        }

        function eliminar(id) {
            swal({
                title: "¿Estás seguro?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.post('controller/PuestoTrabajoController.php', {accion: 'eliminar', idPuestoTrabajo: id}, function(res) {
                        if(res.estatus) {
                            swal("Eliminado", res.mensaje, "success");
                            listarPuestos();
                        } else {
                            swal("Error", res.mensaje, "error");
                        }
                    }, 'json');
                }
            });
        }

        function limpiarFormulario() {
            $('#idPuestoTrabajo').val('');
            $('#nombre').val('');
            $('#idAreaTrabajo').val('');
            $('#accion').val('guardar');
        }
    </script>
</body>
</html>