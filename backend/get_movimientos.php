<?php
include 'db_connection.php';

// Establecer el encabezado de respuesta para JSON
header('Content-Type: application/json');

// Consulta para obtener los últimos 10 movimientos incluyendo el rol
$sql = "
    SELECT movimientos.fecha, movimientos.tipo, usuarios.email AS usuario, usuarios.rol
    FROM movimientos
    JOIN usuarios ON movimientos.usuarios_id = usuarios.id
    ORDER BY movimientos.fecha DESC
    LIMIT 10
";

$result = $conn->query($sql);
$movimientos = [];

if ($result) {
    // Recorrer y almacenar los movimientos en un arreglo
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movimientos[] = $row;
        }
    }
    // Devolver el resultado en formato JSON
    echo json_encode($movimientos);
} else {
    // En caso de error en la consulta, devolver el error
    echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
