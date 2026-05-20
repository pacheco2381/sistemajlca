<?php
$servername = "localhost";
$username = "root";  // Cambia si usas otro usuario
$password = "";      // Cambia si tienes contraseña en MySQL
$dbname = "peoplewater";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
