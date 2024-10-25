<?php
include 'db_connection.php';
include 'bcv_rate.php';
$tasa_bcv = obtenerTasaDolarBCV();

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];

    // Buscar el socio en la base de datos por cédula.
    $sql_socio = "SELECT id, nombre, apellido, ultimo_mes_pagado, deuda FROM socios WHERE cedula = ?";
    $stmt_socio = $conn->prepare($sql_socio);
    $stmt_socio->bind_param("s", $cedula);
    $stmt_socio->execute();
    $resultado_socio = $stmt_socio->get_result();

    if ($resultado_socio->num_rows > 0) {
        $socio = $resultado_socio->fetch_assoc();
        $socio_id = $socio['id'];
        $nombre_socio = $socio['nombre'] . " " . $socio['apellido'];
        $ultimo_mes_pagado = $socio['ultimo_mes_pagado'];  // Nuevo campo
        $deuda_actual = $socio['deuda'];  // Columna existente de deuda

        // Obtener la fecha actual y la fecha del último pago
        $fecha_actual = new DateTime();
        $fecha_ultimo_pago = new DateTime($ultimo_mes_pagado);

        // Calcular la diferencia de meses
        $diferencia_meses = $fecha_ultimo_pago->diff($fecha_actual)->m + ($fecha_ultimo_pago->diff($fecha_actual)->y * 12);

        // Costo mensual (por ejemplo, $70)
        $costo_mensual = 70;
        
        // Deuda total: meses sin pagar * costo mensual
        $deuda_calculada = $diferencia_meses * $costo_mensual;

        // Actualizar la deuda si es diferente a la calculada
        if ($deuda_calculada != $deuda_actual) {
            $sql_actualizar_deuda = "UPDATE socios SET deuda = ? WHERE id = ?";
            $stmt_actualizar_deuda = $conn->prepare($sql_actualizar_deuda);
            $stmt_actualizar_deuda->bind_param("di", $deuda_calculada, $socio_id);
            $stmt_actualizar_deuda->execute();
        }

        // Devolver los datos en formato JSON para el frontend
        $response = [
            'nombre' => $nombre_socio,
            'deuda_total' => $deuda_calculada,
            'meses_morosos' => $diferencia_meses,
            'tasa_bcv' => $tasa_bcv,
            'deuda_bolivares' => $deuda_calculada * $tasa_bcv
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Socio no encontrado.']);
    }
}
?>
