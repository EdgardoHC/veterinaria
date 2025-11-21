$(document).ready(function () {
    cargarRazas();

    $("#frmRaza").submit(function (e) {
        e.preventDefault();

        let accion = $("#idRaza").val() === "" ? "crear" : "actualizar";
        $.post("controller/RazaController.php",
            $(this).serialize() + "&accion=" + accion,
            function (res) {
                if (res.ok) {
                    console.log(res.ok);
                    alert("Raza " + (accion === "crear" ? "guardado" : "actualizado") + " correctamente");

                    $("#modalNuevaRaza").modal("hide");
                    $("#frmRaza")[0].reset();
                    $("#idRaza").val("");
                    $("#tituloModalRaza").text("Nueva Raza");
                    $("#btnGuardarRaza").text("Guardar");

                    cargarRazas();
                } else {
                    alert("Ocurrió un error al " + (accion === "crear" ? "guardar" : "actualizar") + " la raza");
                }
            },
            "json"
        );
    });
});

function cargarRazas() {
    $.post("controller/RazaController.php",
        {
            accion: "listar"
        }, function (data) {
            let filas = "";

            $.each(data, function (i, r) {
                filas += `
                <tr>
                    <td>${r.idRaza}</td>
                    <td>${r.nombre}</td>
                    <td>
                        <button class="btn btn-sm btn-warning"
                            onclick="editarRaza(${r.idRaza}, '${r.nombre}')">
                            Editar
                        </button>

                        <button class="btn btn-sm btn-danger"
                            onclick="eliminarRaza(${r.idRaza})">
                            Eliminar
                        </button>
                    </td>
                </tr>`;
            });

            $("#tablaRazas tbody").html(filas);

        }, "json");
}

function nuevaRaza() {
    $("#idRaza").val('');
    $("#nombre").val('');

    $("#tituloModalRaza").text("Nueva Raza");
    $("#btnGuardarRaza").text("Guardar");

    $("#modalNuevaRaza").modal("show");
}

function editarRaza(id, nombre) {
    $("#idRaza").val(id);
    $("#nombre").val(nombre);

    $("#tituloModalRaza").text("Editar raza");
    $("#btnGuardarRaza").text("Actualizar");

    $("#modalNuevaRaza").modal("show");
}

function eliminarRaza(id) {
    if (confirm("¿Confirma que desea eliminar esta raza?")) {
        
        $.post("controller/RazaController.php",
            { accion: "eliminar", idRaza: id },
            function (res) {
                if (res.ok) {
                    
                    alert("Raza eliminada correctamente");
                    cargarRazas();
                } else {
                    alert("No se pudo eliminar la raza");
                }
            },
            "json"
        );
    }
}