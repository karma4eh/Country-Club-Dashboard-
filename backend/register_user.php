<?php
// register_user.php
include 'db_connection.php';
session_start();

// Verifica si el usuario ha iniciado sesión

// Verifica que el formulario haya sido enviado correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica que todos los datos estén presentes
    if (empty($_POST['nombre_completo']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password']) || empty($_POST['rol'])) {
        header("Location: ../src/users.php?error=datos_incompletos");
        exit();
    }

    // Limpia y valida los datos recibidos
    $nombre_completo = mysqli_real_escape_string($conn, trim($_POST['nombre_completo']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm_password']));
    $rol = mysqli_real_escape_string($conn, trim($_POST['rol']));

    // Verifica si las contraseñas coinciden
    if ($password !== $confirm_password) {
        header("Location: ../src/users.php?error=contrasenas_no_coinciden");
        exit();
    }

    // Valida el formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../src/users.php?error=correo_invalido");
        exit();
    }

    // Hashea la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verifica si el correo electrónico ya existe en la base de datos
    $query_check_email = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check_email = mysqli_prepare($conn, $query_check_email);
    mysqli_stmt_bind_param($stmt_check_email, 's', $email);
    mysqli_stmt_execute($stmt_check_email);
    mysqli_stmt_store_result($stmt_check_email);

    if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
        header("Location: ../src/users.php?error=correo_existente");
        exit();
    }

    // Inserta el nuevo usuario en la base de datos
    $query = "INSERT INTO usuarios (nombre_completo, email, password, rol) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $nombre_completo, $email, $hashed_password, $rol);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../src/users.php?success=usuario_agregado");
    } else {
        // Manejo de errores detallado para saber si falló la inserción
        echo "Error al agregar el usuario: " . mysqli_error($conn);
        header("Location: ../src/users.php?error=error_al_agregar");
    }
    exit();
} else {
    header("Location: ../src/users.php?error=metodo_no_permitido");
    exit();
}
?>
