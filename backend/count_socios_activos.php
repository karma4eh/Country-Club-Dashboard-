<?php

include 'db_connection.php'; 

$total_activos = 0; 

// Contar socios activos
$query = "SELECT COUNT(*) AS total_activos FROM socios WHERE estado = 'activo';"; 

$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $total_activos = $row['total_activos'];
} else {
    $total_activos = 0; // En caso de que falle la consulta
}

// Cerrar la conexión
$conn->close();

?>
