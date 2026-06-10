<?php
// Evitar que el navegador almacene en caché la respuesta de la sesión
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json');

session_start();

// Validar usando 'username' tal como está guardado en tu login.php
if (isset($_SESSION['username']) && isset($_SESSION['nombre'])) {
    echo json_encode([
        'logged_in' => true,
        'nombre'    => $_SESSION['nombre']
    ]);
} else {
    echo json_encode([
        'logged_in' => false,
        'nombre'    => 'Invitado'
    ]);
}
exit();
