<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel</title>
</head>
<body>
  <h1>Bienvenido <?php echo $usuario['nombre']; ?>!</h1>
  <p>Has iniciado sesión como <?php echo $usuario['apodo']; ?>.</p>
  <form id="frmLogout">
    <button type="submit">Cerrar Sesión</button>
  </form>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $("#frmLogout").submit(function(e){
        e.preventDefault();
        $.post("controller/LoginController.php", {accion: "logout"}, function(res){
            if(res.ok){
                window.location.href = "index.php?page=login";
            }
        }, "json");
    });
  </script>
</body>
</html>
