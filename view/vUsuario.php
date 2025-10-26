<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="view/js/usuario.js"></script>
    <title>Mantenimiento de clientes</title>
</head>

<body>

    <div class="container-fluid">
        <h3>Gestion de usuarios</h3>
        <button class="btn btn-sm btn-primary" onclick="nuevoUsuario()">Nuevo usuario</button>
        <a class="btn btn-sm btn-secondary" href="index.php?page=reporteUsuarios" target="_blank" rel="noopener">Informe PDF</a>

        <hr>
        <table class="table table-hover table-striped" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Apodo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

    <!-- Modal nuevo usuario-->
    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmUsuario" method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="hidden" id="idUsuario" name="idUsuario">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="100">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="correo">Correo</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="apodo">Apodo</label>
                                <input type="text" class="form-control" id="apodo" name="apodo" required>
                            </div>
                            <div class="form-group col-md-6" id="grupoPwd">
                                <label for="pwd">Contrasena</label>
                                <input type="password" class="form-control" id="pwd" name="pwd" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="btnGuardar" type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


</body>

</html>
