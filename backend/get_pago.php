<?php
include 'db_connection.php';

if (isset($_GET['socio_id'])) {
    $socio_id = $_GET['socio_id'];
    $stmt = $conn->prepare("SELECT id_pago, descripcion, monto, fecha_pago FROM pagos WHERE socios_id = ?");
    $stmt->bind_param("i", $socio_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }

    echo json_encode($pagos);  // Devuelve los pagos en formato JSON
    $stmt->close();
} else {
    echo json_encode(["error" => "ID de socio no proporcionado"]);
}

$conn->close();
?>
