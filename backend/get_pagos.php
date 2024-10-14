<?php
include '../backend/db_connection.php';

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Aquí puedes usar tu consulta SQL para obtener los socios
$query = "SELECT * FROM socios WHERE nombre LIKE ? OR cedula LIKE ?"; // Modifica esto según tu base de datos

$stmt = $conn->prepare($query);
$searchParam = "%$searchQuery%";
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Muestra los datos de los socios
    echo "<tr>
            <td class='px-6 py-3 border-b border-gray-700 text-gray-300'>{$row['nombre']}</td>
            <td class='px-6 py-3 border-b border-gray-700 text-gray-300'>{$row['cedula']}</td>
            <td class='px-6 py-3 border-b border-gray-700 text-gray-300'>{$row['accion']}</td>
            <td class='px-6 py-3 border-b border-gray-700 text-gray-300'>{$row['estado']}</td>
            <td class='px-6 py-3 border-b border-gray-700 text-gray-300'>{$row['vencimiento']}</td>
            <td class='px-6 py-3 border-b border-gray-700 text-gray-300'>
                <button onclick='verSocio({$row['id']})' class='text-blue-500'>Ver</button>
            </td>
          </tr>";
}
?>
