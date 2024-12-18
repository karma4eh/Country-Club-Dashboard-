<?php
include 'db_connection.php';

if (isset($_GET['socio_id'])) {
    $socio_id = $_GET['socio_id'];
    $query = "SELECT fecha_pago, monto FROM pagos WHERE socio_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $socio_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
    echo json_encode($pagos);
} else {
    echo json_encode(["error" => "ID del socio no proporcionado"]);
}
?>
