$(document).ready(function () {

    const hoy = new Date();
    const offset = hoy.getTimezoneOffset();
    const localDate = new Date(hoy.getTime() - (offset*60*1000));
    const fechaInput = localDate.toISOString().slice(0, 16); 
    
    $('#fecha').val(localDate.toISOString().slice(0, 10));
  
    function showMessage(type, text) {
      const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
      const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                      ${icon} ${text}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
      $('#msgResult').html(html);
    }
  
    function cargarSelect(url, $select, formatter) {
      $select.prop('disabled', true);
      const originalText = $select.find('option:first').text();
      $select.html(`<option value="">${originalText} (Cargando...)</option>`);
  
      $.getJSON(url, function (data) {
        let html = '<option value="">-- Seleccione --</option>';
        
        if(data && data.length > 0){
            data.forEach(item => {
              const idVal = item.id || item.idmascota || item.idUsuario;
              html += `<option value="${idVal}">${formatter(item)}</option>`;
            });
        }
  
        $select.html(html);
        $select.prop('disabled', false);
  
      }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Error cargando select: ", textStatus, errorThrown);
        $select.html('<option value="">Error al cargar</option>');
      });
    }

    const URL_LIST_MASCOTAS = '../../controller/MascotaController.php?op=listar_json';
    const URL_STORE         = '../../controller/ExpedienteController.php?op=store';

  
    $('#formExpediente').on('submit', function (e) {
      e.preventDefault();
      $('#btnGuardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
  
      const formData = new FormData(this);
  
      $.ajax({
        url: URL_STORE,
        method: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        timeout: 10000
      })
      .done(function (resp) {
        if (resp && resp.success) {
          showMessage('success', 'Expediente creado correctamente (ID: ' + (resp.id || 'OK') + ')');
          $('#formExpediente')[0].reset();
          $('#fecha').val(localDate.toISOString().slice(0, 10));
        } else {
          showMessage('danger', 'Error: ' + (resp.message || 'No se pudo crear expediente'));
        }
      })
      .fail(function (xhr, status, err) {
        console.error('AJAX Error', status, err, xhr.responseText);
        showMessage('danger', 'Error de servidor o sesión expirada.');
      })
      .always(function () {
        $('#btnGuardar').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Expediente');
      });
    });
});