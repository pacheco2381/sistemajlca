<?php

require 'conexion.php';

$mes = $_GET['mes'];

$sql = "SELECT * FROM informes WHERE mes = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mes);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    echo json_encode([
        'success' => true,
        'data' => $resultado->fetch_assoc()
    ]);
} else {

    echo json_encode([
        'success' => false
    ]);
}
