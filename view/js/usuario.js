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
                alert(res.message || "Operacion realizada");
                $("#modalNuevoUsuario").modal("hide");
                resetFormulario();
                cargarUsuarios();
            } else {
                alert(res.message || "No se pudo completar la operacion");
            }
        }).fail(function (xhr) {
            const mensaje = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "Error de comunicacion con el servidor";
            alert(mensaje);
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
            alert(res.message || "No se pudo obtener la informacion de usuarios");
            return;
        }

        const usuarios = res.data || [];
        const $tbody = $("#tablaUsuarios tbody");
        $tbody.empty();

        if (usuarios.length === 0) {
            const $fila = $("<tr>");
            $fila.append($("<td>").attr("colspan", 5).addClass("text-center").text("No hay usuarios registrados"));
            $tbody.append($fila);
            return;
        }

        usuarios.forEach(function (u) {
            const $fila = $("<tr>");
            $fila.append($("<td>").text(u.nombre));
            $fila.append($("<td>").text(u.apellidos));
            $fila.append($("<td>").text(u.email));
            $fila.append($("<td>").text(u.apodo));

            const $acciones = $("<td>");
            const $btnEditar = $("<button>")
                .addClass("btn btn-sm btn-warning mr-1 btn-editar")
                .text("Editar")
                .data("usuario", u);

            const $btnEliminar = $("<button>")
                .addClass("btn btn-sm btn-danger btn-eliminar")
                .text("Eliminar")
                .data("id", u.idUsuario);

            $acciones.append($btnEditar, $btnEliminar);
            $fila.append($acciones);
            $tbody.append($fila);
        });
    }).fail(function (xhr) {
        const mensaje = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "Error de comunicacion con el servidor";
        alert(mensaje);
    });
}

function nuevoUsuario() {
    resetFormulario();
    $("#modalNuevoUsuario").modal("show");
}

function prepararEdicion(usuario) {
    if (!usuario) {
        alert("No se pudo obtener la informacion del usuario");
        return;
    }

    $("#idUsuario").val(usuario.idUsuario);
    $("#nombre").val(usuario.nombre);
    $("#apellidos").val(usuario.apellidos);
    $("#email").val(usuario.email);
    $("#apodo").val(usuario.apodo);
    $("#pwd").val("");

    $("#exampleModalLabel").text("Editar usuario");
    $("#btnGuardar").text("Actualizar");
    $("#grupoPwd").hide();
    $("#pwd").removeAttr("required");

    $("#modalNuevoUsuario").modal("show");
}

function solicitarEliminacion(id) {
    if (!id) {
        alert("Identificador de usuario invalido");
        return;
    }

    if (!confirm("Seguro que desea eliminar el usuario?")) {
        return;
    }

    $.ajax({
        url: "controller/UsuarioController.php",
        method: "POST",
        data: { accion: "eliminar", idUsuario: id },
        dataType: "json"
    }).done(function (res) {
        if (res.ok) {
            alert(res.message || "Usuario eliminado");
            cargarUsuarios();
        } else {
            alert(res.message || "No se pudo eliminar el usuario");
        }
    }).fail(function (xhr) {
        const mensaje = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "Error de comunicacion con el servidor";
        alert(mensaje);
    });
}

function resetFormulario() {
    $("#frmUsuario")[0].reset();
    $("#idUsuario").val("");
    $("#exampleModalLabel").text("Nuevo usuario");
    $("#btnGuardar").text("Guardar");
    $("#grupoPwd").show();
    $("#pwd").attr("required", true);
}

function toggleFormulario(bloquear) {
    $("#frmUsuario").find("input, select, textarea, button[type=submit]").prop("disabled", bloquear);
    $("#btnGuardar").text(
        bloquear ? "Procesando..." : ($("#idUsuario").val() === "" ? "Guardar" : "Actualizar")
    );
}
