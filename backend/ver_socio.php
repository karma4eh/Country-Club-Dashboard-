<?php
include_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del socio
    $query = "SELECT * FROM socios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        ?>

        <div>
            <p><strong>Nombre:</strong> <?php echo $socio['nombre']; ?></p>
            <p><strong>Apellido:</strong> <?php echo $socio['apellido']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $socio['direccion']; ?></p>
            <p><strong>Cédula:</strong> <?php echo $socio['cedula']; ?></p>
            <p><strong>Número:</strong> <?php echo $socio['numero']; ?></p>
            <p><strong>Correo:</strong> <?php echo $socio['correo']; ?></p>
            <p><strong>Acción:</strong> <?php echo $socio['accion']; ?></p>
            <p><strong>Estado:</strong> <?php echo $socio['estado']; ?></p>
            <p><strong>Vencimiento:</strong> <?php echo $socio['vencimiento']; ?></p>
        </div>

        <?php
    } else {
        echo "<p>No se encontraron datos para este socio.</p>";
    }
} else {
    echo "<p>ID de socio no proporcionado.</p>";
}
?>
