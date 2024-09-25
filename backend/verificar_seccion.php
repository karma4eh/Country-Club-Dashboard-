<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirigir al login si no está autenticado
    exit();
}
?>
