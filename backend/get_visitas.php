<?php
include 'db_connection.php';

if (isset($_GET['socio_id']) && isset($_GET['month']) && isset($_GET['year'])) {
    $socio_id = $_GET['socio_id'];
    $month = $_GET['month'];
    $year = $_GET['year'];

    // Preparar la consulta para filtrar por mes y año
    $stmt = $conn->prepare("
        SELECT fecha_entrada 
        FROM visitas 
        WHERE socios_id = ? 
        AND MONTH(fecha_entrada) = ? 
        AND YEAR(fecha_entrada) = ?
    ");
    $stmt->bind_param("iii", $socio_id, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $visitas = [];
    while ($row = $result->fetch_assoc()) {
        $visitas[] = date('j', strtotime($row['fecha_entrada'])); // Guardar solo el día
    }

    echo json_encode($visitas); // Devuelve las visitas en formato JSON
    $stmt->close();
} else {
    echo json_encode(["error" => "Parámetros faltantes."]);
}
?>
