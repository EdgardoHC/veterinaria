$(document).ready(function () {

  const hoy = new Date();
  const y = hoy.getFullYear();
  const m = String(hoy.getMonth() + 1).padStart(2, '0');
  const d = String(hoy.getDate()).padStart(2, '0');
  $('#fecha').val(`${y}-${m}-${d}`);

  function showMessage(type, text) {
    const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${text}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>`;
    $('#msgResult').html(html);
  }

  function cargarSelect(url, $select, formatter) {
    $select.prop('disabled', true);
    $select.html('<option value="">Cargando...</option>');

    $.getJSON(url, function (data) {
      let html = '<option value="">-- Seleccione --</option>';
      data.forEach(item => {
        html += `<option value="${item.id}">${formatter(item)}</option>`;
      });
      $select.html(html);
      $select.prop('disabled', false);

    }).fail(function () {
      $select.html('<option value="">No se pudo cargar</option>');
      $select.prop('disabled', false);
    });
  }

  // Rutas del backend 
  const URL_LIST_MASCOTAS = 'http://localhost/veterinaria/index.php?action=listarMascotas';
  const URL_LIST_VETE = 'http://localhost/veterinaria/index.php?action=listarVeterinarios';
  const URL_STORE = 'http://localhost/veterinaria/index.php?action=expediente-store';

  // Cargar mascotas
  cargarSelect(URL_LIST_MASCOTAS, $('#mascota_id'), function (it) {
    return `${it.nombre} — ${it.encargado_nombre || ''}`;
  });

  // Cargar veterinarios
  cargarSelect(URL_LIST_VETE, $('#veterinario_id'), function (it) {
    return `${it.nombre_completo || (it.nombre + ' ' + (it.apellidos || ''))}`;
  });

  // Cancelar
  $('#btnCancelar').click(function () {
    $('#formExpediente')[0].reset();  
    $('#msgResult').empty();          
    
    $('#fecha').val(`${y}-${m}-${d}`);
  });

  // Guardar
  $('#formExpediente').on('submit', function (e) {
    e.preventDefault();
    $('#btnGuardar').prop('disabled', true);

    const payload = {
      mascota_id: $('#mascota_id').val(),
      fecha: $('#fecha').val(),
      descripcion: $('#descripcion').val(),
      veterinario_id: $('#veterinario_id').val()
    };

    // Validaciones
    if (!payload.mascota_id) {
      showMessage('danger', 'Selecciona una mascota');
      $('#btnGuardar').prop('disabled', false);
      return;
    }
    if (!payload.descripcion || payload.descripcion.trim().length < 3) {
      showMessage('danger', 'Describe el motivo (mín 3 caracteres)');
      $('#btnGuardar').prop('disabled', false);
      return;
    }
    if (!payload.veterinario_id) {
      showMessage('danger', 'Selecciona un veterinario');
      $('#btnGuardar').prop('disabled', false);
      return;
    }

    // Enviar AJAX al backend
    $.ajax({
      url: URL_STORE,
      method: 'POST',
      data: payload,
      dataType: 'json',
      timeout: 10000
    })
    .done(function (resp) {
      if (resp && resp.success) {
        showMessage('success', 'Expediente creado correctamente (ID: ' + (resp.id || '—') + ')');
        
        // Limpiar después de éxito 
        $('#formExpediente')[0].reset();
        $('#fecha').val(`${y}-${m}-${d}`);
      } else {
        showMessage('danger', 'Error: ' + (resp.message || 'No se pudo crear expediente'));
      }
    })
    .fail(function (xhr, status, err) {
      console.error('AJAX Error', status, err, xhr.responseText);
      showMessage('danger', 'Error de servidor. Revisa consola.');
    })
    .always(function () {
      $('#btnGuardar').prop('disabled', false);
    });

  });

});
