<?php
include 'db_connection.php';
session_start();

// Verificar que el usuario esté autenticado
include 'verificar_seccion.php';

// Verificar que se haya enviado el parámetro de ID de socio
if (isset($_GET['socio_id'])) {
    $socio_id = $_GET['socio_id'];

    // Consulta SQL para obtener el historial de pagos
    $sql = "SELECT fecha, monto FROM pagos WHERE socio_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Vinculamos el parámetro
        $stmt->bind_param("i", $socio_id);

        // Ejecutamos la consulta
        $stmt->execute();

        // Obtenemos el resultado
        $result = $stmt->get_result();

        // Verificamos si hay pagos
        if ($result->num_rows > 0) {
            $pagos = [];

            // Extraemos los pagos
            while ($row = $result->fetch_assoc()) {
                $pagos[] = [
                    'fecha' => $row['fecha'],
                    'monto' => $row['monto']
                ];
            }

            // Devolvemos los pagos en formato JSON
            echo json_encode($pagos);
        } else {
            // Si no hay pagos, devolvemos un array vacío
            echo json_encode([]);
        }

        // Cerramos la sentencia
        $stmt->close();
    } else {
        // Si hubo error en la consulta
        echo json_encode(['error' => 'Error al preparar la consulta']);
    }
} else {
    // Si no se recibió el ID del socio
    echo json_encode(['error' => 'ID de socio no proporcionado']);
}

// Cerramos la conexión
$conn->close();
?>
