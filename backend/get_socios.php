<?php
include 'db_connection.php';

// Obtener el término de búsqueda de la consulta
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Escapar el término de búsqueda para evitar inyecciones SQL
$searchTerm = $conn->real_escape_string($searchTerm);

// Crear la consulta SQL con selección aleatoria y límite de 10 resultados
$sql = "SELECT id, nombre, apellido, cedula, accion, estado, saldo 
        FROM socios 
        WHERE nombre LIKE '%$searchTerm%' OR 
              apellido LIKE '%$searchTerm%' OR 
              cedula LIKE '%$searchTerm%' OR 
              accion LIKE '%$searchTerm%' 
        ORDER BY RAND() 
        LIMIT 10";

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
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm'>$" . $row['saldo'] . "</td>";
        
        // Botones para abrir el modal y ver perfil
        echo "<td class='px-6 py-4 border-b border-gray-700 text-sm space-x-2'>
                <button class='px-4 py-2 bg-yellow-500 text-gray-900 rounded hover:bg-yellow-600' 
                onclick='verDetallesSocio(" . $row['id'] . ")'>Ver</button>
                <a href='ver_perfil.php?cedula=" . $row['cedula'] . "' class='px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500'>
                    Perfil
                </a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='px-6 py-4 text-center text-sm text-gray-400'>No se encontraron socios</td></tr>";
}

$conn->close();
?>
