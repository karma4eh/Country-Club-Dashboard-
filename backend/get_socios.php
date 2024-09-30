<?php
// Conexión a la base de datos
include 'db_connection.php';

// Obtener los primeros 5 socios
$sql = "SELECT id, nombre, apellido, cedula, accion, estado, vencimiento FROM socios LIMIT 100"; // Asegúrate de que 'id' sea el identificador único del socio
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Generar filas de la tabla para cada socio
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>" . $row['nombre'] . " " . $row['apellido'] . "</td>";
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>V-" . $row['cedula'] . "</td>";
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>" . $row['accion'] . "</td>";
        
        $estado_clase = ($row['estado'] == 'activo') ? 'text-green-500' : 'text-red-500';
        $estado_texto = ($row['estado'] == 'activo') ? 'Activo' : 'Inactivo';
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm $estado_clase'>" . $estado_texto . "</td>";
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>" . $row['vencimiento'] . "</td>";
        
        // Botón para abrir el modal con detalles del socio
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>
                <button class='px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500' 
                onclick='verDetallesSocio(" . $row['id'] . ")'>Ver</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='px-6 py-4 text-center text-sm text-gray-400'>No se encontraron socios</td></tr>";
}

$conn->close();
?>
