<?php
// session_start();
// if (!isset($_SESSION['usuario'])) {
//     header('Location: ../index.php');
//     exit();
// }
?>

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
        .historial-item { border-left: 4px solid #007bff; padding-left: 15px; margin-bottom: 15px; }
        .receta-item { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <div class="container-fluid mt-4">
        <!-- SECCIÓN 1: Buscador de Expediente -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="fas fa-search"></i> Buscar Expediente</h5>
                    </div>
                    <div class="card-body">
                        <form id="formBuscarExpediente">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="busqueda" class="required">Buscar por ID Expediente o Nombre de Mascota:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="busqueda" 
                                                   placeholder="Ej: 123 o 'Firulais'" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: Información del Expediente -->
        <div id="infoExpediente" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5><i class="fas fa-paw"></i> Información de la Mascota</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Mascota:</strong> <span id="infoNombre"></span>
                            </div>
                            <div class="col-md-2">
                                <strong>Raza:</strong> <span id="infoRaza"></span>
                            </div>
                            <div class="col-md-2">
                                <strong>Edad:</strong> <span id="infoEdad"></span>
                            </div>
                            <div class="col-md-2">
                                <strong>Sexo:</strong> <span id="infoSexo"></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Encargado:</strong> <span id="infoEncargado"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: Historial Previo -->
        <div id="historialPrevio" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5><i class="fas fa-history"></i> Últimas Consultas</h5>
                    </div>
                    <div class="card-body">
                        <div id="listaHistorial">
                            <!-- Aquí se cargará el historial via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: Formulario de Consulta Actual -->
        <div id="formularioConsulta" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5><i class="fas fa-notes-medical"></i> Nueva Consulta</h5>
                    </div>
                    <div class="card-body">
                        <form id="formConsulta">
                            <input type="hidden" id="idexpediente" name="idexpediente">
                            <input type="hidden" id="idmascota" name="idmascota">
                            
                            <div class="row">
                                <!-- Columna Izquierda: Datos Básicos -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="peso" class="required">Peso (kg):</label>
                                        <input type="number" class="form-control" id="peso" name="peso" 
                                               step="0.1" min="0" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="altura" class="required">Altura (cm):</label>
                                        <input type="number" class="form-control" id="altura" name="altura" 
                                               step="0.1" min="0" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="fecha" class="required">Fecha de Consulta:</label>
                                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" 
                                               value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Columna Derecha: Observaciones -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="resumen" class="required">Resumen / Observaciones:</label>
                                        <textarea class="form-control" id="resumen" name="resumen" 
                                                  rows="4" placeholder="Observaciones generales de la consulta..." required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="diagnostico" class="required">Diagnóstico:</label>
                                        <textarea class="form-control" id="diagnostico" name="diagnostico" 
                                                  rows="4" placeholder="Diagnóstico médico..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: Recetas Relacionadas -->
        <div id="seccionRecetas" class="row mb-4" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-prescription-bottle-alt"></i> Recetas</h5>
                            <button type="button" class="btn btn-light btn-sm" onclick="abrirModalReceta()">
                                <i class="fas fa-plus"></i> Nueva Receta
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="listaRecetas">
                            <p class="text-muted">No hay recetas registradas para esta consulta.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 6: Botones de Acción -->
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="volver()">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                    
                    <div>
                        <button type="button" class="btn btn-info mr-2" onclick="abrirModalReceta()">
                            <i class="fas fa-prescription-bottle-alt"></i> Agregar Receta
                        </button>
                        
                        <button type="button" class="btn btn-success" onclick="guardarConsulta()">
                            <i class="fas fa-save"></i> Guardar Consulta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Recetas -->
    <div class="modal fade" id="modalReceta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Agregar Nueva Receta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formReceta">
                        <input type="hidden" id="receta_idexpedientedetalle" name="idexpedientedetalle">
                        
                        <div class="form-group">
                            <label for="receta_fecha" class="required">Fecha:</label>
                            <input type="datetime-local" class="form-control" id="receta_fecha" name="fecha" 
                                   value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="receta_descripcion" class="required">Descripción:</label>
                            <textarea class="form-control" id="receta_descripcion" name="descripcion" 
                                      rows="4" placeholder="Descripción de la receta..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="receta_dosis" class="required">Dosis:</label>
                            <textarea class="form-control" id="receta_dosis" name="dosis" 
                                      rows="3" placeholder="Indicaciones de dosis..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarReceta()">Guardar Receta</button>
                </div>
            </div>
        </div>
    </div>

    <?php // include '../includes/footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let expedienteDetalleId = null;

        $(document).ready(function() {
            $('#formBuscarExpediente').submit(function(e) {
                e.preventDefault();
                buscarExpediente();
            });
        });

        function buscarExpediente() {
            const busqueda = $('#busqueda').val();
            
            if (busqueda.trim() === '') {
                alert('Por favor ingrese un término de búsqueda');
                return;
            }
            
            $.ajax({
                url: '../controller/ConsultaController.php?action=buscarExpediente',
                type: 'POST',
                data: { busqueda: busqueda },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        mostrarInformacionExpediente(response.data);
                        cargarHistorial(response.data.idmascota);
                    } else {
                        alert('Expediente no encontrado: ' + response.message);
                        ocultarSecciones();
                    }
                },
                error: function() {
                    alert('Error al buscar expediente');
                }
            });
        }

        function mostrarInformacionExpediente(data) {
            // Mostrar secciones
            $('#infoExpediente, #historialPrevio, #formularioConsulta, #seccionRecetas').show();
            
            // Llenar información
            $('#infoNombre').text(data.nombre_mascota);
            $('#infoRaza').text(data.raza);
            $('#infoEdad').text(calcularEdad(data.fechanacimiento));
            $('#infoSexo').text(data.sexo);
            $('#infoEncargado').text(data.encargado_nombre + ' ' + data.encargado_apellido);
            $('#idexpediente').val(data.idexpediente);
            $('#idmascota').val(data.idmascota);
        }

        function cargarHistorial(idMascota) {
            $.ajax({
                url: '../controller/ConsultaController.php?action=obtenerHistorial',
                type: 'POST',
                data: { idmascota: idMascota },
                dataType: 'json',
                success: function(historial) {
                    mostrarHistorial(historial);
                }
            });
        }

        function mostrarHistorial(historial) {
            const listaHistorial = $('#listaHistorial');
            
            if (historial.length === 0) {
                listaHistorial.html('<p class="text-muted">No hay consultas previas registradas.</p>');
                return;
            }
            
            let html = '';
            historial.forEach(consulta => {
                html += `
                    <div class="historial-item">
                        <strong>Fecha:</strong> ${formatFecha(consulta.fecha)}<br>
                        <strong>Veterinario:</strong> ${consulta.veterinario}<br>
                        <strong>Peso:</strong> ${consulta.peso} kg | <strong>Altura:</strong> ${consulta.altura} cm<br>
                        <strong>Resumen:</strong> ${consulta.resumen}<br>
                        <strong>Diagnóstico:</strong> ${consulta.diagnostico}
                    </div>
                `;
            });
            
            listaHistorial.html(html);
        }

        function guardarConsulta() {
            if (!validarFormularioConsulta()) {
                return;
            }
            
            const formData = new FormData(document.getElementById('formConsulta'));
            
            $.ajax({
                url: '../controller/ConsultaController.php?action=guardarConsulta',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Consulta guardada exitosamente');
                        expedienteDetalleId = response.id;
                        $('#receta_idexpedientedetalle').val(response.id);
                        // Opcional: limpiar formulario o redirigir
                    } else {
                        alert('Error al guardar: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error al guardar la consulta');
                }
            });
        }

        function abrirModalReceta() {
            if (!expedienteDetalleId) {
                alert('Primero debe guardar la consulta antes de agregar recetas');
                return;
            }
            
            $('#modalReceta').modal('show');
        }

        function guardarReceta() {
            const formData = new FormData(document.getElementById('formReceta'));
            
            $.ajax({
                url: '../controller/ConsultaController.php?action=agregarReceta',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Receta guardada exitosamente');
                        $('#modalReceta').modal('hide');
                        // Limpiar formulario de receta
                        $('#formReceta')[0].reset();
                        // Actualizar lista de recetas
                        // cargarRecetas();
                    } else {
                        alert('Error al guardar receta: ' + response.message);
                    }
                }
            });
        }

        function validarFormularioConsulta() {
            const peso = $('#peso').val();
            const altura = $('#altura').val();
            const resumen = $('#resumen').val();
            const diagnostico = $('#diagnostico').val();
            
            if (!peso || !altura || !resumen || !diagnostico) {
                alert('Por favor complete todos los campos requeridos');
                return false;
            }
            
            return true;
        }

        function calcularEdad(fechaNacimiento) {
            const nacimiento = new Date(fechaNacimiento);
            const hoy = new Date();
            let años = hoy.getFullYear() - nacimiento.getFullYear();
            let meses = hoy.getMonth() - nacimiento.getMonth();
            
            if (meses < 0) {
                años--;
                meses += 12;
            }
            
            if (años > 0) {
                return años + ' año' + (años > 1 ? 's' : '');
            } else {
                return meses + ' mes' + (meses > 1 ? 'es' : '');
            }
        }

        function formatFecha(fechaStr) {
            const fecha = new Date(fechaStr);
            return fecha.toLocaleDateString('es-ES') + ' ' + fecha.toLocaleTimeString('es-ES');
        }

        function ocultarSecciones() {
            $('#infoExpediente, #historialPrevio, #formularioConsulta, #seccionRecetas').hide();
        }

        function volver() {
            window.history.back();
        }
    </script>
</body>
</html>