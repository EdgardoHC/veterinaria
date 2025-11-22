$(document).ready(function () {
 
    cargarCartillas();
 
    // Guardar (agregar o editar)
    $('#frmCartilla').on('submit', function (e) {
        e.preventDefault();
 
        const id = $('#idcartillavacunacion').val();
        const accion = id ? 'editar' : 'agregar';
 
        $.ajax({
            url: `../controller/cartillaController.php?action=${accion}`,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function (res) {
            if (res.success) {
                $('#modalNuevaCartilla').modal('hide');
                cargarCartillas();
            } else {
                alert('Error: ' + res.message);
            }
        }).fail(function (xhr) {
            alert('Error al enviar datos.');
            console.error(xhr.responseText);
        });
    });
 
});
 
// NUEVA CARTILLA
function nuevaCartilla() {
    $('#tituloModal').text('Nueva Cartilla');
    $('#frmCartilla')[0].reset();
    $('#idcartillavacunacion').val('');
    $('#modalNuevaCartilla').modal('show');
}
 
// CARGAR TABLA
function cargarCartillas() {
    $.ajax({
        url: '../controller/cartillaController.php?action=listar',
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {
        let html = '';
        data.forEach(c => {
            html += `
                <tr>
                    <td>${c.idcartillavacunacion}</td>
                    <td>${c.idmascota}</td>
                    <td>${c.idusuario}</td>
                    <td>${c.fecha}</td>
                    <td>${c.peso}</td>
                    <td>${c.altura}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editar"
                            data-id="${c.idcartillavacunacion}"
                            data-idmascota="${c.idmascota}"
                            data-idusuario="${c.idusuario}"
                            data-fecha="${c.fecha}"
                            data-peso="${c.peso}"
                            data-altura="${c.altura}">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm eliminar"
                            data-id="${c.idcartillavacunacion}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tablaCartillas').html(html);
    });
}
 
// BOTÓN EDITAR
$(document).on('click', '.editar', function () {
    $('#tituloModal').text('Editar Cartilla');
 
    $('#idcartillavacunacion').val($(this).data('id'));
    $('#idmascota').val($(this).data('idmascota'));
    $('#idusuario').val($(this).data('idusuario'));
    $('#fecha').val($(this).data('fecha'));
    $('#peso').val($(this).data('peso'));
    $('#altura').val($(this).data('altura'));
 
    $('#modalNuevaCartilla').modal('show');
});
 
// ELIMINAR
$(document).on('click', '.eliminar', function () {
    if (!confirm("¿Eliminar registro?")) return;
 
    $.post('../controller/cartillaController.php?action=eliminar',
        { id: $(this).data('id') },
        function (res) {
            if (res.success) cargarCartillas();
            else alert('No se pudo eliminar.');
        }, 'json');
});
 