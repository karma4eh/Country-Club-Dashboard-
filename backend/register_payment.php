<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $monto_dolares = $_POST['monto_dolares'];
    $descripcion = $_POST['descripcion'];

    // Obtener el ID del socio
    $query = "SELECT id, nombre, apellido, saldo FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        $socio_id = $socio['id'];
        $nombre_socio = $socio['nombre'];
        $apellido_socio = $socio['apellido'];
        $saldo_actual = $socio['saldo'];

        // Registrar el pago
        $query = "INSERT INTO pagos (socios_id, descripcion, monto) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isd', $socio_id, $descripcion, $monto_dolares);

        if ($stmt->execute()) {
            // Actualizar el último mes pagado y saldo
            $query_update = "UPDATE socios SET saldo = saldo + ?, ultimo_mes_pagado = NOW() WHERE id = ?";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bind_param('di', $monto_dolares, $socio_id);
            $stmt_update->execute();

            // Calcular nuevo saldo
            $nuevo_saldo = $saldo_actual + $monto_dolares;

            // Generar la factura
            $factura = [
                'nombre' => $nombre_socio,
                'apellido' => $apellido_socio,
                'cedula' => $cedula,
                'descripcion' => $descripcion,
                'monto_dolares' => number_format($monto_dolares, 2, '.', ''),
                'nuevo_saldo' => number_format($nuevo_saldo, 2, '.', ''),
                'fecha_pago' => date('Y-m-d H:i:s'),
            ];

            // Devolver la respuesta como JSON
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'factura' => $factura]);
            exit(); // Asegúrate de salir después de devolver la respuesta
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar el pago']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Socio no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no válido']);
}
?>
