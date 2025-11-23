 
let modal;
document.addEventListener("DOMContentLoaded", () => {
    modal = new bootstrap.Modal(document.getElementById("modalReceta"));
    cargarRecetas();
    cargarExpedientes();
});
 
// Cargar datos
function cargarRecetas() {
    fetch("../controller/recetaController.php?action=listar")
        .then(r => r.json())
        .then(data => {
            let tbody = document.querySelector("#tablaRecetas tbody");
            tbody.innerHTML = "";
 
            data.forEach(receta => {
                tbody.innerHTML += `
                    <tr>
                        <td>${receta.idreceta}</td>
                        <td>${receta.fecha}</td>
                        <td>${receta.descripcion}</td>
                        <td>${receta.idexpedientedetalle}</td>
                        <td>${receta.dosis}</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="editarReceta(${receta.idreceta}, '${receta.fecha}', '${receta.descripcion}', ${receta.idexpedientedetalle}, '${receta.dosis}')">
                                Editar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarReceta(${receta.idreceta})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });
        });
}
 
function cargarExpedientes() {
    fetch("../controller/recetaController.php?action=listarExpedientes")
        .then(r => r.json())
        .then(data => {
            let select = document.getElementById("idexpedientedetalle");
            select.innerHTML = "";
            data.forEach(x => {
                select.innerHTML += `<option value="${x.idexpedientedetalle}">${x.idexpedientedetalle}</option>`;
            });
        });
}
 
// Crear nueva receta
function abrirModalNuevaReceta() {
    document.getElementById("idreceta").value = "";
    document.getElementById("fecha").value = obtenerFechaActual();
    document.getElementById("descripcion").value = "";
    document.getElementById("dosis").value = "";
 
    modal.show();
}
 
function obtenerFechaActual() {
    const fecha = new Date();
    const y = fecha.getFullYear();
    const m = String(fecha.getMonth() + 1).padStart(2, '0');
    const d = String(fecha.getDate()).padStart(2, '0');
    const h = String(fecha.getHours()).padStart(2, '0');
    const min = String(fecha.getMinutes()).padStart(2, '0');
 
    return `${y}-${m}-${d}T${h}:${min}`;
}
 
// Guardar
function guardarReceta() {
    let id = document.getElementById("idreceta").value;
    let fecha = document.getElementById("fecha").value;
    let descripcion = document.getElementById("descripcion").value;
    let expediente = document.getElementById("idexpedientedetalle").value;
    let dosis = document.getElementById("dosis").value;
 
    fecha = fecha.replace("T", " ") + ":00";
 
    let form = new FormData();
    form.append("fecha", fecha);
    form.append("descripcion", descripcion);
    form.append("idexpedientedetalle", expediente);
    form.append("dosis", dosis);
 
    let url = "../controller/recetaController.php?action=" + (id ? "editar" : "agregar");
    if (id) form.append("idreceta", id);
 
    fetch(url, { method: "POST", body: form })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                cargarRecetas();
                modal.hide();
            } else {
                alert("Error: " + resp.message);
            }
        });
}
 
// Editar
function editarReceta(id, fecha, descripcion, expediente, dosis) {
 
    // Convertir formato YYYY-MM-DD HH:MM:SS
    let fechaLocal = fecha.replace(" ", "T").slice(0, 16);
 
    document.getElementById("idreceta").value = id;
    document.getElementById("fecha").value = fechaLocal;
    document.getElementById("descripcion").value = descripcion;
    document.getElementById("idexpedientedetalle").value = expediente;
    document.getElementById("dosis").value = dosis;
 
    modal.show();
}
 
// Eliminar
function eliminarReceta(id) {
    if (!confirm("¿Eliminar esta receta?")) return;
 
    let form = new FormData();
    form.append("id", id);
 
    fetch("../controller/recetaController.php?action=eliminar", {
        method: "POST",
        body: form
    })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) cargarRecetas();
        });
}
 