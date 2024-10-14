<?php
include 'db_connection.php'; // Conexión a la base de datos

$query = isset($_GET['query']) ? $_GET['query'] : '';
$response = [];

if ($query) {
    // Realiza la búsqueda en la base de datos
    $stmt = $conn->prepare("SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo, cedula, numero, correo, accion, estado, vencimiento FROM socios WHERE nombre LIKE ? OR apellido LIKE ? OR cedula LIKE ?");
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm); // Añade cedula al bind_param
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

    $stmt->close();
}

echo json_encode($response); // Devuelve los resultados como JSON
?>
