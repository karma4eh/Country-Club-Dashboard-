<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT nombre, apellido, cedula, accion, estado, vencimiento FROM socios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        echo json_encode($socio);
    } else {
        echo json_encode(['error' => 'Socio no encontrado']);
    }

    $stmt->close();
}

$conn->close();
?>
