<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];

    // Consulta para obtener datos del socio en base a su cédula
    $query = "SELECT id, nombre, apellido, saldo FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        $id = $socio['id'];
        $nombre = $socio['nombre']; // Obtener primer nombre
        $apellido = $socio['apellido']; // Obtener primer apellido
        $saldo = $socio['saldo'];

        // Inicializa la deuda en bolívares
        $deuda_bolivares = 0;

        // Solo calcular deuda en bolívares si el saldo es negativo
        if ($saldo < 0) {
            // Obtener la tasa del BCV
            include 'bcv_rate.php';
            $tasa_bcv = obtenerTasaDolarBCV();
            
            // Calcular la deuda total en bolívares usando el saldo negativo
            $deuda_total = abs($saldo); // Usar el valor absoluto del saldo para la deuda total
            $deuda_bolivares = $deuda_total * $tasa_bcv; // Esto ahora debería dar el valor correcto
        } else {
            // Si el saldo es positivo, no hay deuda
            $deuda_total = 0; // La deuda total es 0 si no hay deuda
        }

        // Devolver la información en formato JSON
        echo json_encode([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'saldo' => $saldo, // Mostrar saldo como negativo si hay deuda
            'deuda_total' => ($saldo < 0) ? $deuda_total : 0, // Mostrar la deuda total si hay deuda
            'tasa_bcv' => ($saldo < 0) ? $tasa_bcv : null, // Mostrar la tasa solo si hay deuda
            'deuda_bolivares' => $deuda_bolivares // Mostrar la deuda en bolívares como un valor positivo
        ]);
    } else {
        echo json_encode(['error' => 'Socio no encontrado']);
    }
}
?>
