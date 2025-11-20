<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?page=login");
    exit();
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Panel de Control</title>
  <link rel="stylesheet" href="view/vendor/bootstrap-4.6.2-dist/css/bootstrap.min.css">
  <script src="view/vendor/jquery3.7.1/jquery.min.js"></script>
  <script src="view/vendor/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?>!</h1>
        <p class="text-muted">Has iniciado sesión como <strong><?php echo htmlspecialchars($usuario['apodo']); ?></strong></p>
      </div>
      <form id="frmLogout">
        <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
      </form>
    </div>

    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Usuarios</h5>
            <p class="card-text">Gestionar usuarios del sistema</p>
            <a href="index.php?page=usuarios" class="btn btn-primary">Ir a Usuarios</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Auditoría</h5>
            <p class="card-text">Consultar registros de auditoría del sistema</p>
            <a href="index.php?page=auditoria" class="btn btn-info">Ir a Auditoría</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Respaldos</h5>
            <p class="card-text">Gestionar respaldos de la base de datos</p>
            <a href="index.php?page=respaldo" class="btn btn-success">Ir a Respaldos</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Reportes</h5>
            <p class="card-text">Generar reportes en PDF</p>
            <a href="index.php?page=reporteUsuarios" target="_blank" class="btn btn-secondary">Reporte de Usuarios</a>
          </div>
        </div>
      </div>
    </div>
  </div>

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
