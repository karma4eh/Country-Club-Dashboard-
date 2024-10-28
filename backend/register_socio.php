<?php
// Incluir el archivo de conexión a la base de datos
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Validar que todos los campos están llenos
    if (empty($nombre) || empty($apellido) || empty($direccion) || empty($cedula) || empty($numero) || empty($correo) || empty($accion) || empty($estado)) {
        echo json_encode(["error" => "Todos los campos son obligatorios. Por favor, verifica e intenta nuevamente."]);
        exit;
    }

    // Validar que la acción, la cédula y el correo no estén ocupados por otro socio
    $sql_check = "SELECT * FROM socios WHERE accion = ? OR cedula = ? OR correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('sss', $accion, $cedula, $correo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si hay resultados, significa que ya existe una acción, cédula o correo duplicado
        echo json_encode(["error" => "Error: La acción, cédula o correo ya está registrado en la base de datos."]);
    } else {
        // Insertar nuevo socio en la base de datos
        $sql = "INSERT INTO socios (nombre, apellido, direccion, cedula, numero, correo, accion, estado, saldo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssss', $nombre, $apellido, $direccion, $cedula, $numero, $correo, $accion, $estado, $deuda);

        if ($stmt->execute()) {
            echo json_encode(["success" => "El socio ha sido registrado exitosamente."]);
        } else {
            echo json_encode(["error" => "Error al registrar el socio: " . $conn->error]);
        }
    }

    // Cerrar la conexión
    $stmt_check->close();
    $conn->close();
}
?>
