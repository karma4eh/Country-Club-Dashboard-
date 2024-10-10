<?php
// Conexión a la base de datos
include '../backend/db_connection.php'; // Asegúrate de que este archivo tiene la conexión correctamente

// Verificamos que se haya pasado la cédula por la URL o por otro método (GET o POST)
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];

    // Preparamos la consulta
    $stmt = $conn->prepare("SELECT * FROM socios WHERE cedula = ?");
    $stmt->bind_param("s", $cedula); // "s" indica que el parámetro es una cadena

    // Ejecutamos la consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificamos si se encontró un resultado
    if ($result->num_rows > 0) {
        // Obtenemos los datos del socio
        $socio = $result->fetch_assoc();
        // Agrega más campos según sea necesario
    } else {
        echo "No se encontró un socio con la cédula proporcionada.";
    }

    // Cerramos la consulta
    $stmt->close();
} else {
    echo "No se ha proporcionado una cédula.";
}

// Cerramos la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Socio - <?php echo htmlspecialchars($socio['nombre']) . ' ' . htmlspecialchars($socio['apellido']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 shadow-lg">
        <nav class="mt-10">
            <a href="dashboard.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">list</span>
                <span class="ml-3">Inicio</span>
            </a>
            <a href="search_partner.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
                <span class="material-icons">search</span>
                <span class="ml-3">Buscar Socio</span>
            </a>
        </nav>
    </div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
        <header class="bg-gray-800 shadow-lg h-16 flex items-center justify-between px-6">
            <div class="flex items-center">
                <img src="../img/logo.png" alt="Logo Country Club" class="h-8 mr-3">
                <h1 class="text-lg font-bold text-gray-100">Country Club</h1>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-gray-400">Bienvenido, Admin</span>
                <a href="../backend/logout.php" class="text-red-500">Cerrar Sesión</a>
                <span class="material-icons">logout</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold mb-6">Perfil del Socio</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-900 p-4 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-4">Datos Personales</h3>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($socio['nombre']) . ' ' . htmlspecialchars($socio['apellido']); ?></p>
                        <p><strong>Cédula:</strong> <?php echo htmlspecialchars($socio['cedula']); ?></p>
                        <p><span class="material-icons inline-block">email</span> <strong>Correo:</strong> <?php echo htmlspecialchars($socio['correo']); ?></p>
                        <p><span class="material-icons inline-block">phone</span> <strong>Teléfono:</strong> <?php echo htmlspecialchars($socio['telefono']); ?></p>
                        <p><strong>Acción:</strong> <?php echo htmlspecialchars($socio['accion']); ?></p>
                        <p><strong>Estado:</strong> <?php echo htmlspecialchars($socio['estado']); ?></p>
                        <p><strong>Vencimiento:</strong> <?php echo htmlspecialchars($socio['vencimiento']); ?></p>
                    </div>
                    <div class="bg-gray-900 p-4 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-4">Opciones</h3>
                        <div class="space-y-4">
                            <a href="editar_socio.php?cedula=<?php echo urlencode($socio['cedula']); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Editar Datos</a>
                            <a href="enviar_correo.php?cedula=<?php echo urlencode($socio['cedula']); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Enviar Correo</a>
                            <a href="registrar_pago.php?cedula=<?php echo urlencode($socio['cedula']); ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Registrar Pago</a>
                            <a href="historial_pagos.php?cedula=<?php echo urlencode($socio['cedula']); ?>" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">Historial de Pagos</a>
                        </div>
                    </div>
                </div>

                <!-- Pagos realizados -->
                <h3 class="text-xl font-semibold mb-4">Historial de Pagos</h3>
                <table class="w-full bg-gray-900 rounded-lg shadow-lg text-gray-100">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Monto</th>
                            <th class="px-4 py-2">Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pago = $pagos_result->fetch_assoc()): ?>
                        <tr class="bg-gray-800 hover:bg-gray-700">
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($pago['fecha']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($pago['monto']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($pago['metodo']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

</body>
</html>
