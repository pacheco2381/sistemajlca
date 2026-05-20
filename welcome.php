<?php
session_start();

// Seguridad: redirigir si no hay sesión activa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Evitar volver atrás con el botón del navegador
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>

<h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
<p>Has iniciado sesión correctamente.</p>
<a href="logout.php">Cerrar sesión</a>