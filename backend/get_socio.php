<?php
include 'db_connection.php';

// Verifica si la cédula está presente en los parámetros de la URL
if (isset($_GET['cedula'])) {
    // Obtiene la cédula proporcionada
    $cedula = $_GET['cedula'];

    // Prepara la consulta para obtener los datos del socio
    $stmt = $conn->prepare("SELECT * FROM socios WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);  // Asocia el parámetro (la cédula) a la consulta
    $stmt->execute();  // Ejecuta la consulta
    $result = $stmt->get_result();  // Obtiene los resultados

    // Verifica si se encontró algún socio con la cédula proporcionada
    if ($result->num_rows > 0) {
        // Obtiene los datos del socio
        $socio = $result->fetch_assoc();

        // Asegúrate de que las fechas estén en el formato adecuado (YYYY-MM-DD)
        $socio['fecha_nacimiento'] = date('Y-m-d', strtotime($socio['fecha_nacimiento']));
        $socio['socio_desde'] = date('Y-m-d', strtotime($socio['socio_desde']));

        // Devuelve los datos del socio en formato JSON
        echo json_encode($socio);
    } else {
        // Si no se encuentra el socio, devuelve un mensaje de error
        echo json_encode(["error" => "Socio no encontrado"]);
    }

    // Cierra la declaración
    $stmt->close();
} else {
    // Si no se proporciona la cédula, devuelve un mensaje de error
    echo json_encode(["error" => "Cédula no proporcionada"]);
}

// Cierra la conexión con la base de datos
$conn->close();
?>
