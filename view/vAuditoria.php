<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
    <title>Auditoría del Sistema</title>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Auditoría del Sistema</h3>
            <div>
                <a class="btn btn-sm btn-secondary" href="index.php?page=dashboard">Volver al Dashboard</a>
                <a class="btn btn-sm btn-primary" href="index.php?page=respaldo">Respaldos</a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Filtros de Búsqueda</h5>
            </div>
            <div class="card-body">
                <form id="frmFiltros">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filtroAccion">Acción</label>
                            <select class="form-control" id="filtroAccion" name="accion">
                                <option value="">Todas</option>
                                <option value="LOGIN">Login</option>
                                <option value="LOGOUT">Logout</option>
                                <option value="CREAR">Crear</option>
                                <option value="ACTUALIZAR">Actualizar</option>
                                <option value="ELIMINAR">Eliminar</option>
                                <option value="CONSULTAR">Consultar</option>
                                <option value="RESPALDO">Respaldo</option>
                                <option value="RESTAURAR">Restaurar</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroTabla">Tabla</label>
                            <input type="text" class="form-control" id="filtroTabla" name="tabla" placeholder="Ej: usuarios">
                        </div>
                        <div class="col-md-2">
                            <label for="filtroFechaDesde">Fecha Desde</label>
                            <input type="date" class="form-control" id="filtroFechaDesde" name="fechaDesde">
                        </div>
                        <div class="col-md-2">
                            <label for="filtroFechaHasta">Fecha Hasta</label>
                            <input type="date" class="form-control" id="filtroFechaHasta" name="fechaHasta">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-primary btn-block" onclick="cargarAuditoria()">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Estadísticas</h5>
            </div>
            <div class="card-body">
                <div id="estadisticas" class="row"></div>
            </div>
        </div>

        <!-- Tabla de auditoría -->
        <div class="card">
            <div class="card-header">
                <h5>Registros de Auditoría</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm" id="tablaAuditoria">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Tabla</th>
                                <th>ID Registro</th>
                                <th>IP</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de detalles -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Auditoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detallesContenido"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            cargarAuditoria();
            cargarEstadisticas();
        });

        function cargarAuditoria() {
            const filtros = {
                accion: $('#filtroAccion').val(),
                tabla: $('#filtroTabla').val(),
                fechaDesde: $('#filtroFechaDesde').val(),
                fechaHasta: $('#filtroFechaHasta').val()
            };

            $.post('controller/AuditoriaController.php', {
                accion: 'listar',
                ...filtros
            }, function(res) {
                if (res.ok) {
                    const tbody = $('#tablaAuditoria tbody');
                    tbody.empty();

                    if (res.data.length === 0) {
                        tbody.append('<tr><td colspan="7" class="text-center">No hay registros</td></tr>');
                        return;
                    }

                    res.data.forEach(function(reg) {
                        const fecha = new Date(reg.fecha).toLocaleString('es-ES');
                        const usuario = reg.nombre && reg.apellidos 
                            ? `${reg.nombre} ${reg.apellidos} (${reg.apodo || 'N/A'})` 
                            : 'Sistema';
                        
                        const row = `
                            <tr>
                                <td>${fecha}</td>
                                <td>${usuario}</td>
                                <td><span class="badge badge-info">${reg.accion}</span></td>
                                <td>${reg.tabla || 'N/A'}</td>
                                <td>${reg.idRegistro || 'N/A'}</td>
                                <td>${reg.ip || 'N/A'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="verDetalles(${reg.idAuditoria}, '${reg.datosAnteriores || ''}', '${reg.datosNuevos || ''}', '${reg.userAgent || ''}')">
                                        Ver Detalles
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }
            }, 'json').fail(function() {
                $('#tablaAuditoria tbody').html('<tr><td colspan="7" class="text-center text-danger">Error al cargar datos</td></tr>');
            });
        }

        function cargarEstadisticas() {
            const fechaDesde = $('#filtroFechaDesde').val() || null;
            const fechaHasta = $('#filtroFechaHasta').val() || null;

            $.post('controller/AuditoriaController.php', {
                accion: 'estadisticas',
                fechaDesde: fechaDesde,
                fechaHasta: fechaHasta
            }, function(res) {
                if (res.ok && res.data.length > 0) {
                    const div = $('#estadisticas');
                    div.empty();

                    res.data.forEach(function(stat) {
                        const card = `
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">${stat.accion}</h6>
                                        <p class="card-text">
                                            <strong>Total:</strong> ${stat.total}<br>
                                            <strong>Usuarios únicos:</strong> ${stat.usuariosUnicos}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        div.append(card);
                    });
                }
            }, 'json');
        }

        function verDetalles(id, datosAnteriores, datosNuevos, userAgent) {
            let contenido = '<div class="row">';
            
            if (datosAnteriores) {
                try {
                    const anterior = JSON.parse(datosAnteriores);
                    contenido += '<div class="col-md-6"><h6>Datos Anteriores:</h6><pre class="bg-light p-3">' + 
                        JSON.stringify(anterior, null, 2) + '</pre></div>';
                } catch (e) {
                    contenido += '<div class="col-md-6"><h6>Datos Anteriores:</h6><pre class="bg-light p-3">' + 
                        datosAnteriores + '</pre></div>';
                }
            }

            if (datosNuevos) {
                try {
                    const nuevo = JSON.parse(datosNuevos);
                    contenido += '<div class="col-md-6"><h6>Datos Nuevos:</h6><pre class="bg-light p-3">' + 
                        JSON.stringify(nuevo, null, 2) + '</pre></div>';
                } catch (e) {
                    contenido += '<div class="col-md-6"><h6>Datos Nuevos:</h6><pre class="bg-light p-3">' + 
                        datosNuevos + '</pre></div>';
                }
            }

            contenido += '</div>';
            
            if (userAgent) {
                contenido += '<div class="mt-3"><h6>User Agent:</h6><p class="bg-light p-2">' + userAgent + '</p></div>';
            }

            $('#detallesContenido').html(contenido);
            $('#modalDetalles').modal('show');
        }

        // Cargar estadísticas cuando cambian las fechas
        $('#filtroFechaDesde, #filtroFechaHasta').change(function() {
            cargarEstadisticas();
        });
    </script>
</body>

</html>

