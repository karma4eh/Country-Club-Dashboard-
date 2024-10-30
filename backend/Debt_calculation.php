<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];

    // Consulta para obtener datos del socio en base a su cédula
    $query = "SELECT id, nombre, apellido, saldo, ultimo_mes_pagado FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        $id = $socio['id'];
        $nombre = $socio['nombre'];
        $apellido = $socio['apellido'];
        $saldo = $socio['saldo'];
        $ultimo_mes_pagado = new DateTime($socio['ultimo_mes_pagado']);

        // Calcula la cantidad de meses adeudados basados en el saldo negativo
        $monto_mensual = 10; // El monto mensual es de $10
        $meses_deuda = ($saldo < 0) ? ceil(abs($saldo) / $monto_mensual) : 0;

        // Generar la lista de meses adeudados
        $mesesAdeudados = [];
        if ($meses_deuda > 0) {
            $fechaAdeudo = clone $ultimo_mes_pagado;
            for ($i = 0; $i < $meses_deuda; $i++) {
                $fechaAdeudo->modify('+1 month');
                $mesesAdeudados[] = $fechaAdeudo->format('F Y');
            }
        }

        // Determinar si el socio es moroso o solvente
        $estado = $saldo < 0 ? "MOROSO" : "SOLVENTE";

        // Devolver la información en formato JSON
        echo json_encode([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'saldo' => $saldo,
            'estado' => $estado,
            'meses_deuda' => $meses_deuda,
            'meses_adeudados_lista' => $mesesAdeudados
        ]);
    } else {
        echo json_encode(['error' => 'Socio no encontrado']);
    }
}
?>
