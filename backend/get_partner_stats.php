<?php
include 'db_connection.php';


$stmt_activos = $conn->prepare("SELECT COUNT(*) AS total_activos FROM socios WHERE estado = ?");
$estado_activo = 'activo';
$stmt_activos->bind_param("s", $estado_activo);
$stmt_activos->execute();
$result_activos = $stmt_activos->get_result();
$total_activos = $result_activos->fetch_assoc()['total_activos'];

$stmt_inactivos = $conn->prepare("SELECT COUNT(*) AS total_inactivos FROM socios WHERE estado = ?");
$estado_inactivo = 'inactivo';
$stmt_inactivos->bind_param("s", $estado_inactivo);
$stmt_inactivos->execute();
$result_inactivos = $stmt_inactivos->get_result();
$total_inactivos = $result_inactivos->fetch_assoc()['total_inactivos'];

$stmt_activos->close();
$stmt_inactivos->close();
$conn->close();

// Devolver datos como JSON
echo json_encode([
    'total_activos' => $total_activos,
    'total_inactivos' => $total_inactivos
]);
?>