<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Gestión de usuarios</title>

    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="view/css/usuarios-style.css">

    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="view/js/usuario.js"></script>
</head>

<body class="users-page">

    <div class="container my-4">

        <!-- Cabecera -->
        <div class="d-flex justify-content-between align-items-center page-header">
            <h3 class="page-title mb-0">
                <i class="fas fa-users-cog mr-2"></i> Gestión de usuarios
            </h3>
            <div class="page-actions">
                <button class="btn btn-sm btn-primary mr-2" onclick="nuevoUsuario()">
                    <i class="fas fa-user-plus"></i> Nuevo usuario
                </button>
                <a class="btn btn-sm btn-secondary" href="index.php?page=reporteUsuarios" target="_blank" rel="noopener">
                    <i class="fas fa-file-pdf"></i> Informe PDF
                </a>
            </div>
        </div>

        <!-- Tabla dentro de una card -->
        <div class="card card-usuarios">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="tablaUsuarios">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Activo / Inactivo</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llena por AJAX (usuario.js) -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal nuevo / editar usuario-->
    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmUsuario" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-row">
                            <input type="hidden" id="idUsuario" name="idUsuario">

                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       required maxlength="100" placeholder="Ej. Ana Pérez">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                       required placeholder="Ej. aperez">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="estado">Estado del usuario</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="">Selecciona un estado</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">Correo</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       required placeholder="Ej. usuario@veterinaria.com">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="rol">Rol del usuario</label>
                                <select class="form-control" id="rol" name="rol" required>
                                    <option value="">Selecciona un rol</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Veterinario</option>
                                    <option value="3">Recepcionista</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6" id="grupoPwd">
                                <label for="pwd">Contraseña</label>
                                <input type="password" class="form-control" id="pwd" name="pwd"
                                       placeholder="Mínimo 6 caracteres">
                            </div>

                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cerrar</button>
                            <button id="btnGuardar" type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
