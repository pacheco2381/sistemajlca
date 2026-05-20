<?php
// Importar la función de validación
require_once "auth.php";

// Ejecutar validación de sesión
$user_level = validarSesion();
?>

<?php
// Incluir el contenido visual del dashboard
include "dash.html";
?>
