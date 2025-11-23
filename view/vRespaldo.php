<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
    <title>Respaldo de Datos</title>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Respaldo de Datos</h3>
            <div>
                <a class="btn btn-sm btn-secondary" href="index.php?page=dashboard">Volver al Dashboard</a>
                <a class="btn btn-sm btn-info" href="index.php?page=auditoria">Auditoría</a>
            </div>
        </div>

        <!-- Crear respaldo -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Crear Nuevo Respaldo</h5>
            </div>
            <div class="card-body">
                <form id="frmCrearRespaldo">
                    <div class="form-group">
                        <label for="comentario">Comentario (opcional)</label>
                        <input type="text" class="form-control" id="comentario" name="comentario" 
                            placeholder="Descripción del respaldo">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span id="spinnerCrear" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Crear Respaldo
                    </button>
                </form>
                <div id="mensajeCrear" class="mt-3"></div>
            </div>
        </div>

        <!-- Lista de respaldos -->
        <div class="card">
            <div class="card-header">
                <h5>Respaldos Disponibles</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="cargarRespaldos()">
                    <span id="spinnerListar" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    Actualizar Lista
                </button>
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tablaRespaldos">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                                <th>Fecha</th>
                                <th>Tamaño</th>
                                <th>Comentario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para restaurar -->
    <div class="modal fade" id="modalRestaurar" tabindex="-1" aria-labelledby="modalRestaurarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalRestaurarLabel">Confirmar Restauración</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>¡ADVERTENCIA!</strong></p>
                    <p>Esta acción restaurará la base de datos con los datos del respaldo seleccionado. 
                    Todos los datos actuales serán sobrescritos.</p>
                    <p>¿Está seguro que desea continuar?</p>
                    <p><strong>Archivo:</strong> <span id="nombreArchivoRestaurar"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" id="btnConfirmarRestaurar">Confirmar Restauración</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar este respaldo?</p>
                    <p><strong>Archivo:</strong> <span id="nombreArchivoEliminar"></span></p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let archivoActual = null;

        $(document).ready(function() {
            cargarRespaldos();

            $('#frmCrearRespaldo').submit(function(e) {
                e.preventDefault();
                crearRespaldo();
            });

            $('#btnConfirmarRestaurar').click(function() {
                if (archivoActual) {
                    restaurarRespaldo(archivoActual);
                    $('#modalRestaurar').modal('hide');
                }
            });

            $('#btnConfirmarEliminar').click(function() {
                if (archivoActual) {
                    eliminarRespaldo(archivoActual);
                    $('#modalEliminar').modal('hide');
                }
            });
        });

        function crearRespaldo() {
            const comentario = $('#comentario').val();
            const btn = $('#frmCrearRespaldo button[type="submit"]');
            const spinner = $('#spinnerCrear');
            const mensaje = $('#mensajeCrear');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            $.post('controller/RespaldoController.php', {
                accion: 'crear',
                comentario: comentario
            }, function(res) {
                spinner.addClass('d-none');
                btn.prop('disabled', false);

                if (res.exito) {
                    mensaje.html('<div class="alert alert-success">' + res.mensaje + '</div>');
                    $('#comentario').val('');
                    cargarRespaldos();
                } else {
                    mensaje.html('<div class="alert alert-danger">' + res.mensaje + '</div>');
                }

                setTimeout(function() {
                    mensaje.fadeOut();
                }, 5000);
            }, 'json').fail(function() {
                spinner.addClass('d-none');
                btn.prop('disabled', false);
                mensaje.html('<div class="alert alert-danger">Error al crear respaldo</div>');
            });
        }

        function cargarRespaldos() {
            const spinner = $('#spinnerListar');
            const tbody = $('#tablaRespaldos tbody');

            spinner.removeClass('d-none');

            $.post('controller/RespaldoController.php', {
                accion: 'listar'
            }, function(res) {
                spinner.addClass('d-none');
                tbody.empty();

                if (res.ok && res.data.length > 0) {
                    res.data.forEach(function(respaldo) {
                        const tamanio = formatearTamanio(respaldo.tamaño);
                        const fecha = new Date(respaldo.fecha).toLocaleString('es-ES');

                        const row = `
                            <tr>
                                <td>${respaldo.archivo}</td>
                                <td>${fecha}</td>
                                <td>${tamanio}</td>
                                <td>${respaldo.comentario || '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="confirmarRestaurar('${respaldo.archivo}')">
                                        Restaurar
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="confirmarEliminar('${respaldo.archivo}')">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="5" class="text-center">No hay respaldos disponibles</td></tr>');
                }
            }, 'json').fail(function() {
                spinner.addClass('d-none');
                tbody.html('<tr><td colspan="5" class="text-center text-danger">Error al cargar respaldos</td></tr>');
            });
        }

        function confirmarRestaurar(archivo) {
            archivoActual = archivo;
            $('#nombreArchivoRestaurar').text(archivo);
            $('#modalRestaurar').modal('show');
        }

        function confirmarEliminar(archivo) {
            archivoActual = archivo;
            $('#nombreArchivoEliminar').text(archivo);
            $('#modalEliminar').modal('show');
        }

        function restaurarRespaldo(archivo) {
            $.post('controller/RespaldoController.php', {
                accion: 'restaurar',
                archivo: archivo
            }, function(res) {
                if (res.exito) {
                    alert('Respaldo restaurado exitosamente');
                    cargarRespaldos();
                } else {
                    alert('Error: ' + res.mensaje);
                }
            }, 'json').fail(function() {
                alert('Error al restaurar respaldo');
            });
        }

        function eliminarRespaldo(archivo) {
            $.post('controller/RespaldoController.php', {
                accion: 'eliminar',
                archivo: archivo
            }, function(res) {
                if (res.exito) {
                    alert('Respaldo eliminado exitosamente');
                    cargarRespaldos();
                } else {
                    alert('Error: ' + res.mensaje);
                }
            }, 'json').fail(function() {
                alert('Error al eliminar respaldo');
            });
        }

        function formatearTamanio(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>
</body>

</html>

