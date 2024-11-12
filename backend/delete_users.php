<?php
// delete_user_backend.php
include 'db_connection.php';
session_start();

// Verifica si el usuario ha iniciado sesión
include_once 'verificar_seccion.php';

// Verifica si se ha proporcionado el ID del usuario a eliminar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$user_id = $_GET['id'];

// Evita que el usuario actual se elimine a sí mismo
if ($user_id == $_SESSION['usuario_id']) {
    die("No puedes eliminar tu propio usuario.");
}

// Elimina el usuario de la base de datos
$query = "DELETE FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);

if (mysqli_stmt_execute($stmt)) {
    echo "Usuario eliminado correctamente.";
    header("Location: ../src/users.php");
    exit();
} else {
    echo "Error al eliminar el usuario: " . mysqli_error($conn);
}
?>
