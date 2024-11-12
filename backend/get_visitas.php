<?php
include 'db_connection.php';

if (isset($_GET['socio_id'])) {
    $socio_id = $_GET['socio_id'];
    $stmt = $conn->prepare("SELECT fecha_entrada FROM visitas WHERE socios_id = ?");
    $stmt->bind_param("i", $socio_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $visitas = [];
    while ($row = $result->fetch_assoc()) {
        $visitas[] = date('j', strtotime($row['fecha_entrada'])); // Guardar solo el día
    }

    echo json_encode($visitas);  // Devuelve las visitas en formato JSON
    $stmt->close();
} else {
    echo json_encode(["error" => "ID de socio no proporcionado"]);
}

$conn->close();
?>
