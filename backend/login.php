<?php
// Incluir el archivo de conexión

include 'db_connection.php'; // Asegúrate de que la ruta es correcta

// Iniciar sesión para poder almacenar datos de usuario
session_start();

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $email = $_POST['email'];  
    $password = $_POST['password'];

    // Preparar la consulta para buscar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña (usa password_verify si están encriptadas)
        if ($password === $user['password']) {
            // Inicio de sesión exitoso
            $_SESSION['username'] = $user['email']; // Guardar el email en la sesión
            header("Location: ../src/dashboard.php"); // Redirigir al dashboard
            exit(); // Asegurarse de que no se ejecute más código
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta.";
            echo "<script>alert('$error');</script>";
            header("Location: ../src/index.html"); // Redirigir de vuelta al login
            exit(); // Detener el código
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado.";
        echo "<script>alert('$error');</script>";
        header("Location: ../src/index.html"); // Redirigir de vuelta al login
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
