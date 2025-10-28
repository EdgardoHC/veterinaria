<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | Veterinaria</title>

    <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- FontAwesome (para los iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Estilos personalizados para el login -->
    <link rel="stylesheet" href="view/css/login-style.css">
</head>
<body>

    <div class="login-container">
        <form class="login-form" id="frmLogin" method="POST" onsubmit="return false;"> <!-- Añadido onsubmit para prevenir envío por defecto -->
            <div class="logo-container">
                <!-- se puede reemplazar 'fas fa-paw' por un <img> de algun logo -->
                <i class="fas fa-paw logo-icon"></i>
            </div>
            <h2>Bienvenido</h2>
            <p class="text-white-50 mb-4">Inicia sesión para continuar</p>

            <div class="input-group-custom">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="form-control" name="usuario" placeholder="Email o Apodo" required autofocus>
            </div>

            <div class="input-group-custom">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="form-control" name="pwd" placeholder="Contraseña" required>
            </div>
            
            <!-- Contenedor para mensajes de error -->
            <div id="msg" class="text-center mb-3"></div>

            <button class="btn btn-lg btn-block btn-login" type="submit">Acceder</button>

            <div class="text-center mt-4">
                <a href="#" class="forgot-password-link">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
    <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>

    <!-- script de login -->
    <script src="view/js/login.js"></script>
</body>
</html>