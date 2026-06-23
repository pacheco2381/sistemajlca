<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

/* =========================================
   CONEXIÓN MYSQL
========================================= */

$conexion = new mysqli(
    "localhost",
    "root",
    "",
    "global2026"
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

/* =========================================
   VALIDAR ARCHIVO
========================================= */

if (!isset($_FILES['excel'])) {
    die("No se recibió ningún archivo.");
}

$archivo = $_FILES['excel']['tmp_name'];

if (!$archivo) {
    die("Error al subir archivo.");
}

/* =========================================
   CARGAR EXCEL
========================================= */

try {

    $spreadsheet = IOFactory::load($archivo);
} catch (Exception $e) {

    die("Error al leer Excel: " . $e->getMessage());
}

/* =========================================
   RELACIÓN HOJAS -> TABLAS
========================================= */

$hojas = [

    'JUNTA 1' => 'junta_1',
    'JUNTA 2' => 'junta_2',
    'JUNTA 3' => 'junta_3',
    'JUNTA 4' => 'junta_4',
    'JUNTA 5' => 'junta_5',
    'JUNTA 6' => 'junta_6',
    'JUNTA 7' => 'junta_7',
    'JUNTA 8' => 'junta_8',

    'AREA COLECTIVA Y E.H' => 'area_colectiva_eh',

    'GLOBAL' => 'global_mensual'
];

/* =========================================
   RECORRER HOJAS
========================================= */

foreach ($hojas as $nombreHoja => $tabla) {

    $sheet = $spreadsheet->getSheetByName($nombreHoja);

    if (!$sheet) {
        continue;
    }

    $highestRow = $sheet->getHighestRow();

    echo "<h3>Importando: {$nombreHoja}</h3>";


    /* =====================================
       IMPORTAR JUNTAS
    ===================================== */

    if (strpos($tabla, 'junta_') !== false) {

        for ($row = 10; $row <= $highestRow; $row++) {

            $mes = trim($sheet->getCell('A' . $row)->getValue());

            if (empty($mes)) {
                continue;
            }

            $sql = "INSERT INTO $tabla (

                mes,

                demandas_ordinarias,
                demandas_especiales,
                demandas_para_procesales,

                laudos_condenatorios,
                laudos_absolutorios,
                laudos_mixtos,

                acuerdos_junta,
                acuerdos_presidencia,

                convenios_cumplimentados,

                audiencias_cde,
                audiencias_ofrecimiento,
                audiencias_desahogo_pruebas,
                audiencias_remates_incidentales,

                resoluciones_interlocutorias,

                desistimiento_caducidad_incompetencia,

                notificaciones_ejecuciones_diligencias,
                notificaciones_actuarios,

                exhortos,

                amparos_directo,
                amparos_indirecto,

                oficios,
                archivo,

                despachos_ejecucion,

                total_asuntos_mes

            ) VALUES (

                ?,?,?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?,?,?,
                ?,?,?,?,?

            )";

            $stmt = $conexion->prepare($sql);

            if (!$stmt) {

                echo "Error prepare: " . $conexion->error;

                continue;
            }

            $datos = [];

            for ($i = 2; $i <= 25; $i++) {

                $valor = $sheet->getCell([$i, $row])->getCalculatedValue();

                if ($valor == '') {
                    $valor = 0;
                }

                $datos[] = (int)$valor;
            }

            $tipos = 's' . str_repeat('i', count($datos));

            $stmt->bind_param(
                $tipos,
                $mes,
                ...$datos
            );

            $stmt->execute();

            echo "Fila {$row} importada<br>";
        }
    }

    /* =====================================
       AREA COLECTIVA
    ===================================== */

    if ($tabla == 'area_colectiva_eh') {

        for ($row = 9; $row <= $highestRow; $row++) {

            $mes = trim($sheet->getCell('A' . $row)->getValue());

            if (empty($mes)) {
                continue;
            }

            $sql = "INSERT INTO area_colectiva_eh (

                mes,
                demandas,
                conciliaciones,
                audiencias,
                convenios_ratificados,
                laudos,
                embargos,
                remates,
                notificaciones,
                registro_sindical,
                deposito_contratos_colectivos,
                deposito_reglamentos_trabajo,
                emplazamientos_huelga,
                convenios_fuera_juicio,
                exhortos,
                amparos,
                oficios,
                archivo,
                total_asuntos_mes

            ) VALUES (

                ?,?,?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?,?

            )";

            $stmt = $conexion->prepare($sql);

            if (!$stmt) {
                echo "Error prepare: " . $conexion->error;
                continue;
            }

            $datos = [];

            for ($i = 2; $i <= 19; $i++) {

                $valor = $sheet->getCell([$i, $row])->getCalculatedValue();

                if ($valor == '') {
                    $valor = 0;
                }

                $datos[] = (int)$valor;
            }

            $tipos = 's' . str_repeat('i', count($datos));

            $stmt->bind_param(
                $tipos,
                $mes,
                ...$datos
            );

            $stmt->execute();

            echo "Fila {$row} importada<br>";
        }
    }

    /* =====================================
       GLOBAL
    ===================================== */

    if ($tabla == 'global_mensual') {

        for ($row = 10; $row <= $highestRow; $row++) {

            $mes = trim($sheet->getCell('A' . $row)->getValue());

            if (empty($mes)) {
                continue;
            }

            $sql = "INSERT INTO global_mensual (

                mes,

                demandas_ordinarias,
                demandas_especiales,
                demandas_para_procesales,

                laudos_condenatorios,
                laudos_absolutorios,
                laudos_mixtos,
                laudos_colectivos,

                resoluciones_interlocutorias,

                acuerdos_junta,
                acuerdos_presidencia_tercerias,
                acuerdos_colectivos,

                convenios_cumplimentados,

                audiencias_cde,
                audiencias_ofrecimiento,
                audiencias_desahogo_pruebas,
                audiencias_area_colectiva,

                remates_incidentales,

                desistimiento_caducidad_incompetencia,

                notificaciones_ejecuciones_embargos,
                notificaciones_actuarios,

                exhortos,

                amparos_directo,
                amparos_indirecto,

                oficios,
                archivo,

                despachos_ejecucion,

                total_asuntos_mes

            ) VALUES (

                ?,?,?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?

            )";

            $stmt = $conexion->prepare($sql);

            if (!$stmt) {
                echo "Error prepare: " . $conexion->error;
                continue;
            }

            $datos = [];

            for ($i = 2; $i <= 28; $i++) {

                $valor = $sheet->getCell([$i, $row])->getCalculatedValue();

                if ($valor == '') {
                    $valor = 0;
                }

                $datos[] = (int)$valor;
            }

            $tipos = 's' . str_repeat('i', count($datos));

            $stmt->bind_param(
                $tipos,
                $mes,
                ...$datos
            );

            $stmt->execute();

            echo "Fila {$row} importada<br>";
        }
    }
}

/* =========================================
   FINALIZAR
========================================= */

echo "<h2>Importación finalizada correctamente.</h2>";

$conexion->close();
