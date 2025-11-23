<?php
// Validar sesión
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) { header("Location: index.php?page=login"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Áreas de Trabajo</title>
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <?php include 'view/componets/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Gestión de Áreas de Trabajo</h2>
        <div class="text-right mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalArea" onclick="limpiarFormulario()">
                <i class="fas fa-plus"></i> Nueva Área
            </button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Área</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaAreas">
                </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalArea" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Datos del Área</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formArea">
                        <input type="hidden" id="idAreaTrabajo" name="idAreaTrabajo">
                        <input type="hidden" id="accion" name="accion" value="guardar">
                        <div class="form-group">
                            <label>Nombre del Área:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cargar datos al iniciar
        $(document).ready(function() {
            listarAreas();
        });

        function listarAreas() {
            $.post('controller/AreaTrabajoController.php', {accion: 'listar'}, function(data) {
                let html = '';
                data.forEach(area => {
                    html += `<tr>
                        <td>${area.idAreaTrabajo}</td>
                        <td>${area.nombre}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editar(${area.idAreaTrabajo}, '${area.nombre}')"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="eliminar(${area.idAreaTrabajo})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
                $('#tablaAreas').html(html);
            }, 'json');
        }

        $('#formArea').submit(function(e) {
            e.preventDefault();
            $.post('controller/AreaTrabajoController.php', $(this).serialize(), function(res) {
                if(res.estatus) {
                    swal("Éxito", res.mensaje, "success");
                    $('#modalArea').modal('hide');
                    listarAreas();
                } else {
                    swal("Error", res.mensaje, "error");
                }
            }, 'json');
        });

        function editar(id, nombre) {
            $('#idAreaTrabajo').val(id);
            $('#nombre').val(nombre);
            $('#accion').val('actualizar');
            $('#modalArea').modal('show');
        }

        function eliminar(id) {
            swal({
                title: "¿Estás seguro?",
                text: "Una vez eliminado, no podrás recuperar este registro",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.post('controller/AreaTrabajoController.php', {accion: 'eliminar', idAreaTrabajo: id}, function(res) {
                        if(res.estatus) {
                            swal("Eliminado", res.mensaje, "success");
                            listarAreas();
                        } else {
                            swal("Error", res.mensaje, "error");
                        }
                    }, 'json');
                }
            });
        }

        function limpiarFormulario() {
            $('#idAreaTrabajo').val('');
            $('#nombre').val('');
            $('#accion').val('guardar');
        }
    </script>
</body>
</html>