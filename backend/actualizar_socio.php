
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

    if ($nombre) $fields[] = "nombre = '$nombre'";
    if ($apellido) $fields[] = "apellido = '$apellido'";
    if ($correo) $fields[] = "correo = '$correo'";
    if ($numero) $fields[] = "numero = '$numero'";
    if ($estado) $fields[] = "estado = '$estado'";
    if ($direccion) $fields[] = "direccion = '$direccion'";

    if (empty($fields)) {
        echo json_encode(['error' => 'No se han proporcionado datos para actualizar']);
        exit;
    }

    $query .= implode(", ", $fields);
    $query .= " WHERE cedula = '$cedula'";

    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error al actualizar los datos', 'sql_error' => $conn->error]);
    }
    
    

    $conn->close();
} else {
    echo json_encode(['error' => 'Método de solicitud no válido']);
}
?>
