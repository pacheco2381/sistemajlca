<?php
function validarSesion()
{
    session_start();

    // 1. Verificar si el usuario está logeado
    if (!isset($_SESSION['username'])) {
        header("Location: login.php"); // Redirigir al login si no está logeado
        exit;
    }

    // 2. Retornar el nivel del usuario (por si lo necesitas)
    return $_SESSION['user_level'];
}
