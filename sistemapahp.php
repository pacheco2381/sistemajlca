<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - SIA JLCA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <span class="navbar-brand">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
      <a class="btn btn-outline-danger" href="logout.php">Cerrar sesión</a>
    </div>
  </nav>

  <div class="container mt-4">
    <h2>Dashboard</h2>
    <p>Aquí los usuarios podrán realizar registros, modificaciones y eliminaciones de datos.</p>
  </div>
</body>

</html>