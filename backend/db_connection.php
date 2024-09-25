<?php

// Configuración de la base de datos
$host = "127.0.0.1";  // Servidor de base de datos
$port = "3306";       // Puerto de MySQL (por defecto es 3306)
$user = "root";       // Usuario de la base de datos (en XAMPP el usuario por defecto es 'root')
$password = "";       // Sin contraseña para MySQL en XAMPP
$database = "Country_DB";  // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
   
}

// Cerrar conexión (opcional si se va a realizar después de todas las consultas)
// $conn->close();
?>

