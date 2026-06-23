<?php

include 'db.php';

$sql = "SELECT * FROM expedientes ORDER BY id DESC";

$resultado = $conn->query($sql);

$datos = [];

while($fila = $resultado->fetch_assoc()){

    $datos[] = [
        "id" => $fila["id"],
        "numExp" => $fila["num_expediente"],
        "serie" => $fila["serie_documental"],
        "descripcion" => $fila["descripcion"],
        "tipo" => $fila["tipo_expediente"],
        "fase" => $fila["fase"],
        "fechaAudiencia" => $fila["fecha_audiencia"],
        "horaAudiencia" => $fila["hora_audiencia"],
        "observaciones" => $fila["observaciones"],
        "unidad" => $fila["unidad_conservacion"],
        "folios" => $fila["numero_folios"],
        "fechaInicio" => $fila["fecha_inicio_vida"],
        "anio" => $fila["anio_expediente"],
        "ugi" => $fila["ugi"],
        "transferencia" => $fila["transferencia_primaria"],
        "indice" => $fila["indice_documental"],
        "estado" => $fila["estado"]
    ];

}

header('Content-Type: application/json');

echo json_encode($datos);

?>