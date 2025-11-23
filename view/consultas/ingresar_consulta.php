<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Consulta - Veterinaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-header { font-weight: bold; }
        .required:after { content: " *"; color: red; }
        .historial-item { border-left: 4px solid #007bff; padding-left: 15px; margin-bottom: 15px; background-color: #f8f9fa; padding: 10px; border-radius: 0 5px 5px 0; }
        .modal-header .btn-close { margin: -0.5rem -0.5rem -0.5rem auto; }
    </style>
</head>
<body>
    
    <div class="container mt-4 mb-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-search"></i> Buscar Expediente</h5>
                    </div>
                    <div class="card-body">
                        <form id="formBuscarExpediente">
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="busqueda" class="form-label required">Buscar por ID Expediente o Nombre de Mascota:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="busqueda" 
                                               placeholder="Ej: 10 o 'Firulais'" required>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="infoExpediente" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card shadow-sm border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-paw"></i> Información de la Mascota</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center text-md-start">
                            <div class="col-md-3 mb-2">
                                <strong>Mascota:</strong> <span id="infoNombre" class="fs-5"></span>
                            </div>
                            <div class="col-md-2 mb-2">
                                <strong>Raza:</strong> <span id="infoRaza"></span>
                            </div>
                            <div class="col-md-2 mb-2">
                                <strong>Color:</strong> <span id="infoColor"></span>
                            </div>
                            <div class="col-md-2 mb-2">
                                <strong>Sexo:</strong> <span id="infoSexo"></span>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Dueño:</strong> <span id="infoEncargado"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="formularioConsulta" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-md"></i> Nueva Consulta Médica</h5>
                    </div>
                    <div class="card-body">
                        <form id="formConsulta">
                            <input type="hidden" id="idexpediente" name="idexpediente">
                            <!-- Id de usuario quemado, cambiar al momento de usarlo con session -->
                            <input type="hidden" id="idusuario" name="idusuario" value="1"> 
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="fecha" class="form-label required">Fecha</label>
                                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="peso" class="form-label required">Peso (lb/kg)</label>
                                        <input type="number" class="form-control" id="peso" name="peso" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="altura" class="form-label required">Altura (cm)</label>
                                        <input type="number" class="form-control" id="altura" name="altura" step="0.01" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="resumen" class="form-label required">Resumen / Motivo</label>
                                        <textarea class="form-control" id="resumen" name="resumen" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diagnostico" class="form-label required">Diagnóstico</label>
                                        <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="historialPrevio" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Historial Clínico</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <div id="listaHistorial">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="location.reload()">
                    <i class="fas fa-arrow-left"></i> Cancelar / Nuevo
                </button>
                <button type="button" class="btn btn-lg btn-success" id="btnGuardar" onclick="guardarConsulta()" style="display: none;">
                    <i class="fas fa-save"></i> Guardar Consulta
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#fecha').val(now.toISOString().slice(0, 16));

            $('#formBuscarExpediente').submit(function(e) {
                e.preventDefault();
                buscarExpediente();
            });
        });

        function buscarExpediente() {
            const busqueda = $('#busqueda').val().trim();
            if (!busqueda) return alert('Escribe algo para buscar');

            $.ajax({
                url: '../../controller/ConsultaController.php?action=buscar',
                type: 'POST',
                data: { busqueda: busqueda },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        mostrarDatos(res.data);
                        cargarHistorial(res.data.idexpediente);
                    } else {
                        alert(res.message);
                        ocultarTodo();
                    }
                },
                error: function(e) {
                    console.error(e);
                    alert("Error de conexión con el servidor");
                }
            });
        }

        function mostrarDatos(data) {
            $('#infoNombre').text(data.nombre_mascota);
            $('#infoRaza').text(data.raza);
            $('#infoColor').text(data.color || 'No especificado');
            $('#infoSexo').text(data.sexo);
            $('#infoEncargado').text(data.encargado_nombre + ' ' + (data.encargado_apellido || ''));
            
            $('#idexpediente').val(data.idexpediente);

            $('#infoExpediente, #formularioConsulta, #historialPrevio, #btnGuardar').fadeIn();
        }

        function cargarHistorial(idExpediente) {
            $.ajax({
                url: '../../controller/ConsultaController.php?action=historial',
                type: 'POST',
                data: { idexpediente: idExpediente },
                dataType: 'json',
                success: function(res) {
                    let html = '';
                    if (res.length > 0) {
                        res.forEach(item => {
                            html += `
                                <div class="historial-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>Fecha: ${item.fecha}</strong>
                                        <span class="badge bg-primary">${item.veterinario || 'Vet'}</span>
                                    </div>
                                    <p class="mb-1"><strong>Diagnóstico:</strong> ${item.diagnostico}</p>
                                    <small class="text-muted">Peso: ${item.peso} | Altura: ${item.altura}</small>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p class="text-muted text-center">Esta mascota no tiene consultas previas.</p>';
                    }
                    $('#listaHistorial').html(html);
                }
            });
        }

        function guardarConsulta() {
            // Validar manual
            if(!$('#peso').val() || !$('#diagnostico').val()) {
                return alert("Llena al menos Peso y Diagnóstico");
            }

            const formData = new FormData(document.getElementById('formConsulta'));

            $.ajax({
                url: '../../controller/ConsultaController.php?action=guardar',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        alert("¡Consulta Guardada con Éxito!");
                        location.reload();
                    } else {
                        alert("Error al guardar: " + res.message);
                    }
                },
                error: function() {
                    alert("Error fatal al guardar");
                }
            });
        }

        function ocultarTodo() {
            $('#infoExpediente, #formularioConsulta, #historialPrevio, #btnGuardar').hide();
        }
    </script>
</body>
</html>