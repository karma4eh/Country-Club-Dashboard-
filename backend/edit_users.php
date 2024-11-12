<?php
// edit_user_backend.php
include 'db_connection.php';
session_start();

// Verifica si el usuario ha iniciado sesión
include_once 'verificar_seccion.php';

// Verifica si se ha proporcionado el ID del usuario a editar
if (!isset($_POST['id']) || empty($_POST['id'])) {
    die("ID de usuario no especificado.");
}

$user_id = $_POST['id'];
$nombre_completo = $_POST['nombre_completo'];
$email = $_POST['email'];
$rol = $_POST['rol'];
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;

// Actualiza los datos del usuario en la base de datos
$query = "UPDATE usuarios SET nombre_completo = ?, email = ?, rol = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'sssi', $nombre_completo, $email, $rol, $user_id);

if (!mysqli_stmt_execute($stmt)) {
    die("Error al actualizar el usuario: " . mysqli_error($conn));
}

// Si se ha proporcionado una nueva contraseña, actualízala
if ($new_password) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query_password = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt_password = mysqli_prepare($conn, $query_password);
    mysqli_stmt_bind_param($stmt_password, 'si', $hashed_password, $user_id);
    if (!mysqli_stmt_execute($stmt_password)) {
        die("Error al actualizar la contraseña: " . mysqli_error($conn));
    }
}

echo "Usuario actualizado correctamente.";
header("Location: ../src/users.php");
exit();
?>
