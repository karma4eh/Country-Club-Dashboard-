<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $monto_dolares = $_POST['monto_dolares'];
    $descripcion = $_POST['descripcion'];

    // Obtener el ID del socio
    $query = "SELECT id FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        $socio_id = $socio['id'];

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

            echo json_encode(['success' => 'Pago registrado correctamente']);
        } else {
            echo json_encode(['error' => 'Error al registrar el pago']);
        }
    } else {
        echo json_encode(['error' => 'Socio no encontrado']);
    }
}
?>
