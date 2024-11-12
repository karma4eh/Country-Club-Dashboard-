<?php
include 'db_connection.php';

if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    $stmt = $conn->prepare("SELECT * FROM socios WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        echo json_encode($socio);  // Devuelve los datos del socio en formato JSON
    } else {
        echo json_encode(["error" => "Socio no encontrado"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Cédula no proporcionada"]);
}

$conn->close();
?>
