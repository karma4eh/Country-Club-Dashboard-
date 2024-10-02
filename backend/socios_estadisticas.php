<?php
include 'db_connection.php';

// Consulta para contar socios activos
$sql_activos = "SELECT COUNT(*) AS total_activos FROM socios WHERE estado = 'activo'";
$result_activos = $conn->query($sql_activos);
$total_activos = $result_activos->fetch_assoc()['total_activos'];

// Consulta 
$sql_inactivos = "SELECT COUNT(*) AS total_inactivos FROM socios WHERE estado = 'inactivo'";
$result_inactivos = $conn->query($sql_inactivos);
$total_inactivos = $result_inactivos->fetch_assoc()['total_inactivos'];

$conn->close();

// Devolver los datos en formato JSON
echo json_encode([
    'activos' => $total_activos,
    'inactivos' => $total_inactivos
]);
?>
