<?php

include 'db.php';

$id = $_POST['id'];

$numExp = $_POST['numExp'];
$serie = $_POST['serie'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo'];
$fase = $_POST['fase'];
$fechaAudiencia = $_POST['fechaAudiencia'];
$horaAudiencia = $_POST['horaAudiencia'];
$observaciones = $_POST['observaciones'];
$unidad = $_POST['unidad'];
$folios = $_POST['folios'];
$fechaInicio = $_POST['fechaInicio'];
$anio = $_POST['anio'];
$ugi = $_POST['ugi'];
$transferencia = $_POST['transferencia'];
$indice = $_POST['indice'];
$estado = $_POST['estado'];

if ($id == "") {

    $sql = "INSERT INTO expedientes(

        num_expediente,
        serie_documental,
        descripcion,
        tipo_expediente,
        fase,
        fecha_audiencia,
        hora_audiencia,
        observaciones,
        unidad_conservacion,
        numero_folios,
        fecha_inicio_vida,
        anio_expediente,
        ugi,
        transferencia_primaria,
        indice_documental,
        estado

    ) VALUES (

        '$numExp',
        '$serie',
        '$descripcion',
        '$tipo',
        '$fase',
        '$fechaAudiencia',
        '$horaAudiencia',
        '$observaciones',
        '$unidad',
        '$folios',
        '$fechaInicio',
        '$anio',
        '$ugi',
        '$transferencia',
        '$indice',
        '$estado'

    )";
} else {

    $sql = "UPDATE expedientes SET

        num_expediente = '$numExp',
        serie_documental = '$serie',
        descripcion = '$descripcion',
        tipo_expediente = '$tipo',
        fase = '$fase',
        fecha_audiencia = '$fechaAudiencia',
        hora_audiencia = '$horaAudiencia',
        observaciones = '$observaciones',
        unidad_conservacion = '$unidad',
        numero_folios = '$folios',
        fecha_inicio_vida = '$fechaInicio',
        anio_expediente = '$anio',
        ugi = '$ugi',
        transferencia_primaria = '$transferencia',
        indice_documental = '$indice',
        estado = '$estado'

        WHERE id = '$id'
    ";
}

if ($conn->query($sql)) {

    echo json_encode([
        "success" => true
    ]);
} else {

    echo json_encode([
        "success" => false,
        "error" => $conn->error
    ]);
}
