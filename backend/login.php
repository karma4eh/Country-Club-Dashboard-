<?php
include 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];  
    $password = $_POST['password'];

    // Preparar la consulta 
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña hasheada
        if (password_verify($password, $user['password'])) {
            // Inicio de sesión exitoso
            $_SESSION['username'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Si tienes roles, los puedes manejar aquí
            header("Location: ../src/dashboard.php"); // Redirigir al dashboard
            exit(); 
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta.";
            header("Location: ../src/index.php?error=" . urlencode($error));
            exit();
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado.";
        header("Location: ../src/index.php?error=" . urlencode($error));
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
