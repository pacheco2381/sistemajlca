<?php
    } elseif ($nombreHoja == 'JUNTA 6') {

        $tabla = 'junta_6';

    } elseif ($nombreHoja == 'JUNTA 7') {

        $tabla = 'junta_7';

    } elseif ($nombreHoja == 'JUNTA 8') {

        $tabla = 'junta_8';

    } elseif ($nombreHoja == 'AREA COLECTIVA Y E.H') {

        $tabla = 'area_colectiva';

    } else {
        continue;
    }

    $filas = $hoja->toArray();

    foreach ($filas as $index => $fila) {

        if ($index < 8) {
            continue;
        }

        $mes = $fila[0] ?? '';

        if (!$mes) {
            continue;
        }

        $demandas = intval($fila[1] ?? 0);
        $laudos = intval($fila[4] ?? 0);
        $acuerdos = intval($fila[7] ?? 0);
        $convenios = intval($fila[9] ?? 0);
        $audiencias = intval($fila[12] ?? 0);
        $notificaciones = intval($fila[15] ?? 0);

        $sql = "INSERT INTO $tabla (
                    mes,
                    demandas_radicadas,
                    laudos,
                    acuerdos,
                    convenios,
                    audiencias,
                    notificaciones
                ) VALUES (
                    '$mes',
                    '$demandas',
                    '$laudos',
                    '$acuerdos',
                    '$convenios',
                    '$audiencias',
                    '$notificaciones'
                )";

        $conexion->query($sql);
    }
}


echo "Importación completada";