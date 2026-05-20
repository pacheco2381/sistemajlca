<?php

/**
 * logout.php
 * Cierra la sesión activa del usuario y lo redirige al formulario de inicio de sesión.
 */

// 1. Iniciar la sesión
// Esto es necesario para poder acceder a la información de la sesión
session_start();

// 2. Destruir todas las variables de sesión
// Esto elimina datos como $_SESSION['username'] y $_SESSION['user_level']
$_SESSION = array();

// 3. Si se desea destruir la cookie de sesión.
// Esto eliminará la cookie del lado del cliente.
// Nota: La cookie de sesión se llama por defecto "PHPSESSID"
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Finalmente, destruir la sesión.
// Esto elimina el archivo de sesión del servidor.
session_destroy();

// 5. Redirigir al usuario a la página de inicio de sesión.
// Aquí se redirige a login.php, que es donde está el formulario.
header("Location: login.php");
exit;
