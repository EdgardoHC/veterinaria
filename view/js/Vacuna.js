$(document).ready(function () {
    cargarVacunas();

    $("#frmVacuna").submit(function (e) {
        e.preventDefault();

        let accion = $("#idvacuna").val() === "" ? "crear" : "actualizar";

        $.post(
            "controller/VacunaController.php",
            $(this).serialize() + "&accion=" + accion,
            function (res) {
                if (res.ok) {
                    alert(
                        "Vacuna " +
                        (accion === "crear" ? "guardada" : "actualizada") +
                        " correctamente"
                    );

                    // Cerrar modal y limpiar formulario
                    $("#modalAgregar").modal("hide");
                    $("#frmVacuna")[0].reset();
                    $("#idvacuna").val("");

                    // Si en la vista tienes estos IDs, se actualizarán; si no, no pasa nada
                    $("#tituloModalVacuna").text("Agregar Vacuna");
                    $("#btnGuardarVacuna").text("Guardar");

                    cargarVacunas();
                } else {
                    alert(
                        "Ocurrió un error al " +
                        (accion === "crear" ? "guardar" : "actualizar") +
                        " la vacuna"
                    );
                }
            },
            "json"
        );
    });
});

function cargarVacunas() {
    $.post(
        "controller/VacunaController.php",
        { accion: "listar" },
        function (data) {
            let filas = "";

            $.each(data, function (i, v) {
                filas += `
                <tr>
                    <td>${v.idvacuna}</td>
                    <td>${v.nombre}</td>
                    <td>
                        <button class="btn btn-sm btn-warning"
                            onclick="editarVacuna(${v.idvacuna}, '${v.nombre}')">
                            Editar
                        </button>

                        <button class="btn btn-sm btn-danger"
                            onclick="eliminarVacuna(${v.idvacuna})">
                            Eliminar
                        </button>
                    </td>
                </tr>`;
            });

            $("#tablaVacunas tbody").html(filas);
        },
        "json"
    );
}

function nuevaVacuna() {
    $("#idvacuna").val("");
    $("#nombre").val("");

    $("#tituloModalVacuna").text("Agregar Vacuna");
    $("#btnGuardarVacuna").text("Guardar");

    $("#modalAgregar").modal("show");
}

function editarVacuna(id, nombre) {
    $("#idvacuna").val(id);
    $("#nombre").val(nombre);

    $("#tituloModalVacuna").text("Editar Vacuna");
    $("#btnGuardarVacuna").text("Actualizar");

    $("#modalAgregar").modal("show");
}

function eliminarVacuna(id) {
    if (confirm("¿Confirma que desea eliminar esta vacuna?")) {
        $.post(
            "controller/VacunaController.php",
            { accion: "eliminar", idvacuna: id },
            function (res) {
                if (res.ok) {
                    alert("Vacuna eliminada correctamente");
                    cargarVacunas();
                } else {
                    alert("No se pudo eliminar la vacuna");
                }
            },
            "json"
        );
    }
}
