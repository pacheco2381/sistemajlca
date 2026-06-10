<?php
session_start();
session_unset();
session_destroy();

// Evitar volver atrás después de cerrar sesión
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: login.php");
exit;
