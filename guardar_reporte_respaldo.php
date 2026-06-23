<?php
    oficios,
    archivo,
    despachos_ejecucion,
    total_mes
)
VALUES (
    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iisiiiiiiiiiiiiiiiiiiiiii",

    $data['usuario_id'],
    $data['ejercicio'],
    $data['mes'],

    $data['ordinarios'],
    $data['especiales'],
    $data['paraprocesales'],

    $data['condenatorios'],
    $data['absolutorios'],
    $data['mixtos'],

    $data['junta'],
    $data['presidencia'],

    $data['convenios'],

    $data['audiencia_cde'],
    $data['audiencia_desahogo'],

    $data['remates_incidentales'],

    $data['resoluciones_interlocutorias'],

    $data['desistimiento_caducidad'],

    $data['ejecuciones_embargos'],
    $data['actuarios'],

    $data['amparo_directo'],
    $data['amparo_indirecto'],

    $data['oficios'],
    $data['archivo'],
    $data['despachos_ejecucion'],

    $data['total_mes']
);

if ($stmt->execute()) {

    echo json_encode([
        'success' => true,
        'message' => 'Registro guardado correctamente'
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => $conn->error
    ]);
}