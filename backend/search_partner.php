<?php
include 'db_connection.php';

// Inicializa el término de búsqueda vacío
$search_term = '';
if (isset($_GET['search_term'])) {
    $search_term = $_GET['search_term'];
}

// Consulta SQL para buscar coincidencias
$query = "SELECT * FROM socios WHERE 
          nombre LIKE ? OR 
          apellido LIKE ? OR 
          cedula = ? OR 
          accion = ?";

$stmt = $conn->prepare($query);
$search_param = "%" . $search_term . "%";
$stmt->bind_param("ssss", $search_param, $search_param, $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>