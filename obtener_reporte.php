<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// ── Validar sesión ──────────────────────────────────────────────────────────
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No autenticado.'
    ]);
    exit;
}

$mes = $_GET['mes'] ?? '';
$tabla_asignada = $_SESSION['tabla_asignada'] ?? '';
// ── Validar formato YYYY-MM ────────────────────────────────────────────────
if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
    echo json_encode([
        'success' => false,
        'message' => 'Formato de mes inválido.'
    ]);
    exit;
}

// ── Tablas permitidas ──────────────────────────────────────────────────────
$tablas_permitidas = [
    'global_mensual',
    'junta_1',
    'junta_2',
    'junta_3',
    'junta_4',
    'junta_5',
    'junta_6',
    'junta_7',
    'junta_8'
];

if (!in_array($tabla_asignada, $tablas_permitidas)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tabla no permitida.'
    ]);
    exit;
}

// ── Consultar reporte ──────────────────────────────────────────────────────
$sql = "SELECT * FROM `$tabla_asignada` WHERE mes = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mes);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows > 0) {

    $fila = $resultado->fetch_assoc();

    // ── Convertir columnas BD → campos formulario ─────────────────────────
    $datos = [
        'ordinarios'     => $fila['demandas_ordinarias'] ?? 0,
        'especiales'     => $fila['demandas_especiales'] ?? 0,
        'paraprocesales' => $fila['demandas_para_procesales'] ?? 0,
        'exhortos'       => $fila['exhortos'] ?? 0,

        'condenatorios'  => $fila['laudos_condenatorios'] ?? 0,
        'absolutorios'   => $fila['laudos_absolutorios'] ?? 0,
        'mixtos'         => $fila['laudos_mixtos'] ?? 0,

        'junta'          => $fila['acuerdos_junta'] ?? 0,
        'convenios'      => $fila['convenios_cumplimentados'] ?? 0,
        'cde'            => $fila['audiencias_cde'] ?? 0,
        'oap'            => $fila['audiencias_ofrecimiento'] ?? 0,
        'desahogo'       => $fila['audiencias_desahogo_pruebas'] ?? 0,

        'resoluciones'   => $fila['resoluciones_interlocutorias'] ?? 0,
        'desistimiento'  => $fila['desistimiento_caducidad_incompetencia'] ?? 0,

        'actuarios'      => $fila['notificaciones_actuarios'] ?? 0,

        'directos'       => $fila['amparos_directo'] ?? 0,
        'indirectos'     => $fila['amparos_indirecto'] ?? 0,

        'oficios'        => $fila['oficios'] ?? 0,
        'archivo'        => $fila['archivo'] ?? 0,
        'despacho'       => $fila['despachos_ejecucion'] ?? 0,
    ];
    // ── Campos variables según tabla ──────────────────────────────────────
    if ($tabla_asignada === 'global_mensual') {

        $datos['presidencia'] = $fila['acuerdos_presidencia_tercerias'] ?? 0;
        $datos['remates'] = $fila['remates_incidentales'] ?? 0;
        $datos['ejecuciones'] = $fila['notificaciones_ejecuciones_embargos'] ?? 0;
    } else {

        $datos['presidencia'] = $fila['acuerdos_presidencia'] ?? 0;
        $datos['remates'] = $fila['audiencias_remates_incidentales'] ?? 0;
        $datos['ejecuciones'] = $fila['notificaciones_ejecuciones_diligencias'] ?? 0;
    }
    echo json_encode([
        'success' => true,
        'existe' => true,
        'datos' => $datos
    ]);
} else {

    echo json_encode([
        'success' => true,
        'existe' => false
    ]);
}

$stmt->close();
$conn->close();
