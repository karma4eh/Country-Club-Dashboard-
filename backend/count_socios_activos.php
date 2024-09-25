<?php
// Conexión a la base de datos
include 'db_connection.php'; // Asegúrate de que la conexión esté incluida

$total_activos = 0; // Inicializar

// Contar socios activos
$query = "SELECT COUNT(*) AS total_activos FROM socios WHERE estado = 'activo';"; // Agregar comillas
// Ajusta el nombre de la tabla y la columna según tu esquema
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
