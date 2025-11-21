$(document).ready(function () {
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
                    alert("Padecimiento " + (accion === "crear" ? "guardado" : "actualizado") + " correctamente");

                    $("#modalNuevoPadecimiento").modal("hide");
                    $("#frmPadecimiento")[0].reset();
                    $("#idPadecimiento").val("");
                    $("#tituloModalPadecimiento").text("Nuevo padecimiento");
                    $("#btnGuardarPadecimiento").text("Guardar");

                    cargarPadecimientos();
                } else {
                    alert("Ocurrió un error al " + (accion === "crear" ? "guardar" : "actualizar") + " el padecimiento");
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
                    alert("Padecimiento eliminado correctamente");
                    cargarPadecimientos();
                } else {
                    alert("No se pudo eliminar el padecimiento");
                }
            },
            "json"
        );
    }
}