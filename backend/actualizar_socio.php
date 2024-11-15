<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurarse de que la cédula esté presente
    if (!isset($_POST['cedula'])) {
        echo json_encode(['error' => 'Cédula no proporcionada']);
        exit;
    }

    $cedula = $_POST['cedula'];
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : null;
    $correo = isset($_POST['correo']) ? $_POST['correo'] : null;
    $numero = isset($_POST['numero']) ? $_POST['numero'] : null;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : null;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;

    // Construir la consulta de actualización
    $query = "UPDATE socios SET ";
    $fields = [];

    if ($nombre) $fields[] = "nombre = ?";
    if ($apellido) $fields[] = "apellido = ?";
    if ($correo) $fields[] = "correo = ?";
    if ($numero) $fields[] = "numero = ?";
    if ($estado) $fields[] = "estado = ?";
    if ($direccion) $fields[] = "direccion = ?";

    if (empty($fields)) {
        echo json_encode(['error' => 'No se han proporcionado datos para actualizar']);
        exit;
    }

    $query .= implode(", ", $fields);
    $query .= " WHERE cedula = ?";

    // Preparar la sentencia
    if ($stmt = $conn->prepare($query)) {
        $params = [];
        $types = '';

        // Agregar los parámetros a la lista según los campos proporcionados
        if ($nombre) { $params[] = $nombre; $types .= 's'; }
        if ($apellido) { $params[] = $apellido; $types .= 's'; }
        if ($correo) { $params[] = $correo; $types .= 's'; }
        if ($numero) { $params[] = $numero; $types .= 's'; }
        if ($estado) { $params[] = $estado; $types .= 's'; }
        if ($direccion) { $params[] = $direccion; $types .= 's'; }

        // La última parámetro es la cédula (para la condición WHERE)
        $params[] = $cedula;
        $types .= 's'; // Tipo para cédula

        // Asociar los parámetros y ejecutar
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Error al actualizar los datos', 'sql_error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error al preparar la consulta', 'sql_error' => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'Método de solicitud no válido']);
}
?>
