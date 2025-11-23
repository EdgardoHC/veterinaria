$(document).ready(function () {
    function mostrarAlerta(tipo, mensaje, tiempo = 4000) {
        const $contenedor = $('#alertContainer');
        if ($contenedor.length === 0) return;
        const id = 'alert-' + Date.now();
        const $alert = $(`<div id="${id}" class="alert alert-${tipo} alert-dismissible fade show" role="alert">${mensaje}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
        $contenedor.append($alert);
        if (tiempo > 0) {
            setTimeout(function() {
                const el = document.getElementById(id);
                if (el) {
                    const inst = bootstrap.Alert.getOrCreateInstance(el);
                    inst.close();
                }
            }, tiempo);
        }
    }
    cargarPadecimientos();

    $("#frmPadecimiento").submit(function (e) {
        e.preventDefault();

        let accion = $("#idPadecimiento").val() === "" ? "crear" : "actualizar";        
        $.post("controller/PadecimientoController.php",            
        $(this).serialize() + "&accion=" + accion,
            function (res) { 
                console.log(res);                
                if (res.ok) {   
                    console.log(res.ok);             
                    mostrarAlerta('success', "Padecimiento " + (accion === "crear" ? "guardado" : "actualizado") + " correctamente");

                    $("#modalNuevoPadecimiento").modal("hide");
                    $("#frmPadecimiento")[0].reset();
                    $("#idPadecimiento").val("");
                    $("#tituloModalPadecimiento").text("Nuevo padecimiento");
                    $("#btnGuardarPadecimiento").text("Guardar");

                    cargarPadecimientos();
                } else {
                    mostrarAlerta('danger', "Ocurrió un error al " + (accion === "crear" ? "guardar" : "actualizar") + " el padecimiento");
                }
            },
            "json"
        );
    });
});

function cargarPadecimientos() {
    $.post("controller/PadecimientoController.php", 
        { 
            accion: "listar" 
        }, function (data) {
        let filas = "";

        $.each(data, function (i, p) {
            filas += `
                <tr>
                    <td>${p.idPadecimiento}</td>
                    <td>${p.nombre}</td>
                    <td>
                        <button class="btn btn-sm btn-warning"
                            onclick="editarPadecimiento(${p.idPadecimiento}, '${p.nombre}')">
                            Editar
                        </button>

                        <button class="btn btn-sm btn-danger"
                            onclick="eliminarPadecimiento(${p.idPadecimiento})">
                            Eliminar
                        </button>
                    </td>
                </tr>`;
        });

        $("#tablaPadecimientos tbody").html(filas);

    }, "json");
}

function nuevoPadecimiento() {
    $("#idPadecimiento").val('');
    $("#nombre").val('');

    $("#tituloModalPadecimiento").text("Nuevo padecimiento");
    $("#btnGuardarPadecimiento").text("Guardar");

    $("#modalNuevoPadecimiento").modal("show");
}

function editarPadecimiento(id, nombre) {
    $("#idPadecimiento").val(id);
    $("#nombre").val(nombre);

    $("#tituloModalPadecimiento").text("Editar padecimiento");
    $("#btnGuardarPadecimiento").text("Actualizar");

    $("#modalNuevoPadecimiento").modal("show");
}

function eliminarPadecimiento(id) {
    if (confirm("¿Confirma que desea eliminar este padecimiento?")) {

        $.post("controller/PadecimientoController.php",
            { accion: "eliminar", idPadecimiento: id },
            function (res) {
                if (res.ok) {
                    mostrarAlerta('success', "Padecimiento eliminado correctamente");
                    cargarPadecimientos();
                } else {
                    mostrarAlerta('danger', "No se pudo eliminar el padecimiento");
                }
            },
            "json"
        );
    }
}