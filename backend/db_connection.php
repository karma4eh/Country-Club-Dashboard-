<?php
include_once 'verificar_seccion.php';
$host = "127.0.0.1";  
$port = "3306";       
$user = "root";       
$password = "";      
$database = "Country_DB";  

// Crear conexión
$conn = new mysqli($host, $user, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
   
}

?>

