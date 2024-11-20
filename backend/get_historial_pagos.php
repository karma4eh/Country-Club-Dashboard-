<?php
include 'db_connection.php';

if (isset($_GET['socio_id'])) {
    $socio_id = intval($_GET['socio_id']);

    // Consulta con límite a los últimos 5 pagos, ordenados por fecha de pago (recientes primero).
    $query = "SELECT descripcion, monto, fecha_pago 
              FROM pagos 
              WHERE socios_id = ? 
              ORDER BY fecha_pago DESC 
              LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $socio_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $pagos = [];

    // Fetch de los pagos
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }

    $stmt->close();

    // Devolvemos los pagos como JSON
    echo json_encode($pagos);
} else {
    // Mensaje de error si no se proporcionó el ID
    echo json_encode(["error" => "No se proporcionó el ID del socio."]);
}
?>
