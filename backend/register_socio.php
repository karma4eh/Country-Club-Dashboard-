<?php
include 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que el ID del usuario esté en la sesión
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(["error" => "Error: ID de usuario no encontrado en la sesión."]);
        exit;
    }

    $usuarios_id = $_SESSION['usuario_id']; // ID del usuario logueado

    // Recibir los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $direccion = trim($_POST['direccion']);
    $cedula = trim($_POST['cedula']);
    $numero = trim($_POST['numero']);
    $correo = trim($_POST['correo']);
    $accion = trim($_POST['accion']);
    $estado = trim($_POST['estado']);
    $deuda = 0.00; // Valor de deuda inicial

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($direccion) || empty($cedula) || empty($numero) || empty($correo) || empty($accion) || empty($estado)) {
        echo json_encode(["error" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Verificar duplicados en acción, cédula o correo
    $sql_check = "SELECT * FROM socios WHERE accion = ? OR cedula = ? OR correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('sss', $accion, $cedula, $correo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode(["error" => "La acción, cédula o correo ya está registrado."]);
    } else {
        // Insertar socio
        $sql = "INSERT INTO socios (nombre, apellido, direccion, cedula, numero, correo, accion, estado, saldo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssss', $nombre, $apellido, $direccion, $cedula, $numero, $correo, $accion, $estado, $deuda);

        if ($stmt->execute()) {
            // Registrar movimiento
            $sql_movimiento = "INSERT INTO movimientos (usuarios_id, tipo, fecha) VALUES (?, 'Registro', NOW())";
            $stmt_movimiento = $conn->prepare($sql_movimiento);
            $stmt_movimiento->bind_param('i', $usuarios_id);
            
            if ($stmt_movimiento->execute()) {
                echo json_encode(["success" => "El socio y el movimiento han sido registrados exitosamente."]);
            } else {
                echo json_encode(["error" => "Error al registrar el movimiento: " . $stmt_movimiento->error]);
            }
            $stmt_movimiento->close();
        } else {
            echo json_encode(["error" => "Error al registrar el socio: " . $stmt->error]);
        }
        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>
