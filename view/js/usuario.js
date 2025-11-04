$(function () {
    cargarUsuarios();

    $("#frmUsuario").on("submit", function (e) {
        e.preventDefault();

        const accion = $("#idUsuario").val() === "" ? "crear" : "actualizar";
        const payload = $(this).serialize() + "&accion=" + accion;

        toggleFormulario(true);

        $.ajax({
            url: "controller/UsuarioController.php",
            method: "POST",
            data: payload,
            dataType: "json"
        }).done(function (res) {
            if (res.ok) {
                Swal.fire({
                    icon: "success",
                    title: accion === "crear" ? "Usuario creado" : "Usuario actualizado",
                    text: res.message || "",
                    timer: 1500,
                    showConfirmButton: false
                });
                $("#modalNuevoUsuario").modal("hide");
                resetFormulario();
                cargarUsuarios();
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "No se pudo completar la operación",
                    text: res.message || "Revisa los datos e inténtalo de nuevo"
                });
            }
        }).fail(function (xhr) {
            // 👇 AQUÍ ES DONDE HEMOS CAMBIADO COSAS
            let mensaje = "Error de comunicación con el servidor";

            if (xhr.responseJSON && xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                // si PHP devuelve texto plano (notice, warning, fatal, etc.)
                mensaje = xhr.responseText;
            }

            console.error("Error AJAX guardar usuario:", xhr); // para ver detalles en la consola

            Swal.fire({
                icon: "error",
                title: "Ups...",
                text: mensaje
            });
        }).always(function () {
            toggleFormulario(false);
        });
    });

    $("#tablaUsuarios tbody").on("click", ".btn-editar", function () {
        const usuario = $(this).data("usuario");
        prepararEdicion(usuario);
    });

    $("#tablaUsuarios tbody").on("click", ".btn-eliminar", function () {
        const id = $(this).data("id");
        solicitarEliminacion(id);
    });
});

function cargarUsuarios() {
    $.ajax({
        url: "controller/UsuarioController.php",
        method: "POST",
        data: { accion: "listar" },
        dataType: "json"
    }).done(function (res) {
        if (!res.ok) {
            Swal.fire({
                icon: "warning",
                title: "No se pudo obtener la información",
                text: res.message || "Intenta recargar la página"
            });
            return;
        }

        const usuarios = res.data || [];
        const $tbody = $("#tablaUsuarios tbody");
        $tbody.empty();

        if (usuarios.length === 0) {
            const $fila = $("<tr>");
            $fila.append(
                $("<td>")
                    .attr("colspan", 6)
                    .addClass("text-center")
                    .text("No hay usuarios registrados")
            );
            $tbody.append($fila);
            return;
        }

        usuarios.forEach(function (u) {
            const $fila = $("<tr>");

            $fila.append($("<td>").text(u.nombrecompleto));
            $fila.append($("<td>").text(u.nombreusuario));
            $fila.append($("<td>").text(u.correoelectronico));
            $fila.append(
                $("<td>").text(
                    u.idrol == 1 ? "Administrador" :
                    u.idrol == 2 ? "Veterinario" : "Recepcionista"
                )
            );

            // Estado con badge bonito
            const $estado = $("<span>")
                .addClass("badge-estado " + (u.estado == 1 ? "activo" : "inactivo"))
                .text(u.estado == 1 ? "Activo" : "Inactivo");
            $fila.append($("<td>").append($estado));

            const $acciones = $("<td>").addClass("text-right");

            const $btnEditar = $("<button>")
                .addClass("btn btn-warning btn-accion mr-1 btn-editar")
                .html('<i class="fas fa-edit"></i> Editar')
                .data("usuario", u);

            const $btnEliminar = $("<button>")
                .addClass("btn btn-danger btn-accion btn-eliminar")
                .html('<i class="fas fa-trash-alt"></i> Eliminar')
                .data("id", u.idusuario);

            $acciones.append($btnEditar, $btnEliminar);
            $fila.append($acciones);
            $tbody.append($fila);
        });

    }).fail(function (xhr) {
        const mensaje = (xhr.responseJSON && xhr.responseJSON.message)
            ? xhr.responseJSON.message
            : "Error de comunicación con el servidor";
        Swal.fire({
            icon: "error",
            title: "Ups...",
            text: mensaje
        });
    });
}

function nuevoUsuario() {
    resetFormulario();
    $("#exampleModalLabel").text("Nuevo usuario");
    $("#btnGuardar").text("Guardar");
    $("#grupoPwd").show();
    $("#modalNuevoUsuario").modal("show");
}

function prepararEdicion(usuario) {
    if (!usuario) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo obtener la información del usuario"
        });
        return;
    }

    $("#idUsuario").val(usuario.idusuario);
    $("#nombre").val(usuario.nombrecompleto);
    $("#username").val(usuario.nombreusuario);
    $("#email").val(usuario.correoelectronico);
    $("#rol").val(usuario.idrol);
    $("#estado").val(usuario.estado);
    $("#pwd").val("");

    $("#exampleModalLabel").text("Editar usuario");
    $("#btnGuardar").text("Actualizar");

    $("#modalNuevoUsuario").modal("show");
}

function solicitarEliminacion(id) {
    if (!id) {
        Swal.fire({
            icon: "warning",
            title: "Identificador inválido",
            text: "No se pudo determinar qué usuario eliminar"
        });
        return;
    }

    Swal.fire({
        title: "¿Eliminar usuario?",
        text: "Este cambio desactivará al usuario en el sistema.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: "controller/UsuarioController.php",
            method: "POST",
            data: { accion: "eliminar", idUsuario: id },
            dataType: "json"
        }).done(function (res) {
            if (res.ok) {
                Swal.fire({
                    icon: "success",
                    title: "Usuario eliminado",
                    text: res.message || "",
                    timer: 1500,
                    showConfirmButton: false
                });
                cargarUsuarios();
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "No se pudo eliminar",
                    text: res.message || "Intenta de nuevo más tarde"
                });
            }
        }).fail(function (xhr) {
            const mensaje = (xhr.responseJSON && xhr.responseJSON.message)
                ? xhr.responseJSON.message
                : "Error de comunicación con el servidor";
            Swal.fire({
                icon: "error",
                title: "Ups...",
                text:mensaje
            });
        });
    });
}

function resetFormulario() {
    $("#frmUsuario")[0].reset();
    $("#idUsuario").val("");
    $("#exampleModalLabel").text("Nuevo usuario");
    $("#btnGuardar").text("Guardar");
    $("#grupoPwd").show();
}

function toggleFormulario(bloquear) {
    $("#frmUsuario")
        .find("input, select, textarea, button[type=submit]")
        .prop("disabled", bloquear);

    $("#btnGuardar").text(
        bloquear
            ? "Procesando..."
            : ($("#idUsuario").val() === "" ? "Guardar" : "Actualizar")
    );
}
