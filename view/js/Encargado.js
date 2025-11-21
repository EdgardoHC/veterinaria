$(document).ready(function () {
    cargarEncargados();

    $("#frmEncargado").submit(function (e) {
        e.preventDefault();

        let accion = $("#idencargado").val() === "" ? "crear" : "actualizar";

        $.post(
            "controller/EncargadoController.php",
            $(this).serialize() + "&accion=" + accion,
            function (res) {
                if (res.ok) {
                    alert(
                        "Encargado " +
                        (accion === "crear" ? "guardado" : "actualizado") +
                        " correctamente"
                    );

                    $("#modalEncargado").modal("hide");
                    $("#frmEncargado")[0].reset();
                    $("#idencargado").val("");

                    $("#tituloModalEncargado").text("Nuevo Encargado");
                    $("#btnGuardarEncargado").text("Guardar");

                    cargarEncargados();
                } else {
                    alert(
                        "Ocurrió un error al " +
                        (accion === "crear" ? "guardar" : "actualizar") +
                        " el encargado"
                    );
                }
            },
            "json"
        );
    });
});

function cargarEncargados() {
    $.post(
        "controller/EncargadoController.php",
        { accion: "listar" },
        function (data) {
            let filas = "";

            $.each(data, function (i, e) {
                filas += `
                <tr>
                    <td>${e.idencargado}</td>
                    <td>${e.nombres}</td>
                    <td>${e.apellidos}</td>
                    <td>${e.telefonomovil}</td>
                    <td>${e.correoelectronico}</td>
                    <td>
                        <button class="btn btn-sm btn-warning"
                            onclick="editarEncargado(${e.idencargado}, '${e.nombres}', '${e.apellidos}', '${e.telefonofijo}', '${e.telefonomovil}', '${e.dni}', '${e.direccion}', '${e.correoelectronico}', '${e.fechanacimiento}', '${e.sexo}')">
                            Editar
                        </button>

                        <button class="btn btn-sm btn-danger"
                            onclick="eliminarEncargado(${e.idencargado})">
                            Eliminar
                        </button>
                    </td>
                </tr>`;
            });

            $("#tablaEncargados tbody").html(filas);
        },
        "json"
    );
}

function nuevoEncargado() {
    $("#idencargado").val("");
    $("#frmEncargado")[0].reset();

    $("#tituloModalEncargado").text("Nuevo Encargado");
    $("#btnGuardarEncargado").text("Guardar");

    $("#modalEncargado").modal("show");
}

function editarEncargado(id, nombres, apellidos, telefonofijo, telefonomovil, dni, direccion, correoelectronico, fechanacimiento, sexo) {
    $("#idencargado").val(id);
    $("#nombres").val(nombres);
    $("#apellidos").val(apellidos);
    $("#telefonofijo").val(telefonofijo);
    $("#telefonomovil").val(telefonomovil);
    $("#dni").val(dni);
    $("#direccion").val(direccion);
    $("#correoelectronico").val(correoelectronico);
    $("#fechanacimiento").val(fechanacimiento);
    $("#sexo").val(sexo);

    $("#tituloModalEncargado").text("Editar Encargado");
    $("#btnGuardarEncargado").text("Actualizar");

    $("#modalEncargado").modal("show");
}

function eliminarEncargado(id) {
    if (confirm("¿Confirma que desea eliminar este encargado?")) {
        $.post(
            "controller/EncargadoController.php",
            { accion: "eliminar", idencargado: id },
            function (res) {
                if (res.ok) {
                    alert("Encargado eliminado correctamente");
                    cargarEncargados();
                } else {
                    alert("No se pudo eliminar el encargado");
                }
            },
            "json"
        );
    }
}
