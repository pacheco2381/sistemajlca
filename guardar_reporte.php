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

// ── Leer JSON ───────────────────────────────────────────────────────────────
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos.'
    ]);
    exit;
}

$mes = trim($data['mes'] ?? '');
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

// ── Verificar si el mes ya existe ──────────────────────────────────────────
$sqlCheck = "SELECT id FROM `$tabla_asignada` WHERE mes = ? LIMIT 1";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $mes);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Este mes ya fue registrado.'
    ]);
    exit;
}

// ── Mapear campos formulario → BD ──────────────────────────────────────────
$col = [
    'demandas_ordinarias'                   => intval($data['ordinarios'] ?? 0),
    'demandas_especiales'                   => intval($data['especiales'] ?? 0),
    'demandas_para_procesales'              => intval($data['paraprocesales'] ?? 0),
    'exhortos'                              => intval($data['exhortos'] ?? 0),
    'laudos_condenatorios'                  => intval($data['condenatorios'] ?? 0),
    'laudos_absolutorios'                   => intval($data['absolutorios'] ?? 0),
    'laudos_mixtos'                         => intval($data['mixtos'] ?? 0),
    'acuerdos_junta'                        => intval($data['junta'] ?? 0),
    'convenios_cumplimentados'              => intval($data['convenios'] ?? 0),
    'audiencias_cde'                        => intval($data['cde'] ?? 0),
    'audiencias_ofrecimiento'               => intval($data['oap'] ?? 0),
    'audiencias_desahogo_pruebas'           => intval($data['desahogo'] ?? 0),
    'resoluciones_interlocutorias'          => intval($data['resoluciones'] ?? 0),
    'desistimiento_caducidad_incompetencia' => intval($data['desistimiento'] ?? 0),
    'notificaciones_actuarios'              => intval($data['actuarios'] ?? 0),
    'amparos_directo'                       => intval($data['directos'] ?? 0),
    'amparos_indirecto'                     => intval($data['indirectos'] ?? 0),
    'oficios'                               => intval($data['oficios'] ?? 0),
    'archivo'                               => intval($data['archivo'] ?? 0),
    'despachos_ejecucion'                   => intval($data['despacho'] ?? 0),
    'total_asuntos_mes'                     => intval($data['total_mes'] ?? 0),
];

// ── Campos especiales según tabla ──────────────────────────────────────────
if ($tabla_asignada === 'global_mensual') {

    $col['acuerdos_presidencia_tercerias'] = intval($data['presidencia'] ?? 0);
    $col['remates_incidentales'] = intval($data['remates'] ?? 0);
    $col['notificaciones_ejecuciones_embargos'] = intval($data['ejecuciones'] ?? 0);
} else {

    $col['acuerdos_presidencia'] = intval($data['presidencia'] ?? 0);
    $col['audiencias_remates_incidentales'] = intval($data['remates'] ?? 0);
    $col['notificaciones_ejecuciones_diligencias'] = intval($data['ejecuciones'] ?? 0);
}

// ── Construir INSERT ───────────────────────────────────────────────────────
$campos = array_keys($col);
$valores = array_values($col);

$listaCampos = '`mes`, `' . implode('`, `', $campos) . '`';
$placeholders = implode(',', array_fill(0, count($campos) + 1, '?'));
$tipos = 's' . str_repeat('i', count($valores));

$sql = "INSERT INTO `$tabla_asignada` ($listaCampos) VALUES ($placeholders)";
$stmt = $conn->prepare($sql);

$params = array_merge([$tipos, $mes], $valores);
$refs = [];
foreach ($params as $k => $v) {
    $refs[$k] = &$params[$k];
}

call_user_func_array([$stmt, 'bind_param'], $refs);

// ── Ejecutar ───────────────────────────────────────────────────────────────
if ($stmt->execute()) {

    echo json_encode([
        'success' => true,
        'message' => 'Informe guardado correctamente.'
    ]);
} else {

    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
