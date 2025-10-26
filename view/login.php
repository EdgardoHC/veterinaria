<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="view/js/login.js"></script>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="view/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">

    <form id="frmLogin" class="form-signin" method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <img class="mb-4" src="" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Inicio de sesión</h1>
        <label for="inputEmail" class="sr-only">Email o Apodo</label>
        <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Apodo o correo" required autofocus>
        <label for="pwd" class="sr-only">Contraseña</label>

        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Contraseña" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
        <p class="mt-5 mb-3 text-muted">&copy;</p>
    </form>
    <div id="msg"></div>


</body>

</html>