<?php
include 'db_connection.php'; 

// Consulta para obtener los pagos
$sql = "
    SELECT p.descripcion, p.monto, p.fecha_pago, CONCAT(s.nombre, ' ', s.apellido) AS nombre_socio
    FROM pagos p
    JOIN socios s ON p.socios_id = s.id  -- Asegúrate de que el ID de la tabla socios es correcto
    WHERE DATE(p.fecha_pago) = CURDATE()  -- Solo pagos del día actual
    ORDER BY p.fecha_pago DESC
";

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        // Generar filas de la tabla para cada pago
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>" . htmlspecialchars($row['nombre_socio']) . "</td>";
            echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>" . htmlspecialchars($row['descripcion']) . "</td>";
            echo "<td class='px-6 py-4 border-b border-gray-700 text-sm text-green-500'>" . htmlspecialchars($row['monto']) . " $</td>"; 
            echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>" . htmlspecialchars($row['fecha_pago']) . "</td>";
            echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>
                    <button class='px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700'>Factura</button>
                  </td>";
            echo "</tr>"; 
        }
    } else {
        echo "<tr><td colspan='5' class='px-6 py-4 text-center text-sm text-gray-400'>No hay pagos registrados.</td></tr>";
    }
} else {
    echo "Error en la consulta: " . $conn->error;
}

$conn->close();
?>
