<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];

    // Consulta para obtener datos del socio en base a su cédula
    $query = "SELECT id, nombre, deuda, ultimo_mes_pagado FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        $id = $socio['id'];
        $nombre = $socio['nombre'];
        $deuda = $socio['deuda'];
        $ultimo_mes_pagado = new DateTime($socio['ultimo_mes_pagado']);

        // Calcular los meses adeudados desde la última fecha de pago
        $fecha_actual = new DateTime();
        $meses_adeudados = $fecha_actual->diff($ultimo_mes_pagado)->m;
        $meses_adeudados += 12 * $fecha_actual->diff($ultimo_mes_pagado)->y;

        // Calcula la deuda en función de los meses adeudados
        $monto_mensual = 70; // Define el monto mensual
        $deuda_total = $deuda + ($meses_adeudados * $monto_mensual);

        // Obtener la tasa del BCV
        include 'bcv_rate.php';
        $tasa_bcv = obtenerTasaDolarBCV();
        $deuda_bolivares = $deuda_total * $tasa_bcv;

        echo json_encode([
            'nombre' => $nombre,
            'deuda_total' => $deuda_total,
            'tasa_bcv' => $tasa_bcv,
            'deuda_bolivares' => $deuda_bolivares
        ]);
    } else {
        echo json_encode(['error' => 'Socio no encontrado']);
    }
}
?>
