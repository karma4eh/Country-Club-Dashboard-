<?php
include '../backend/db_connection.php';

// Inicializa el término de búsqueda vacío
$search_term = '';
if (isset($_GET['search_term'])) {
    $search_term = $_GET['search_term'];
}

// Consulta SQL para buscar coincidencias
$query = "SELECT * FROM socios WHERE 
          nombre LIKE ? OR 
          apellido LIKE ? OR 
          cedula = ? OR 
          accion = ?";

$stmt = $conn->prepare($query);
$search_param = "%" . $search_term . "%";
$stmt->bind_param("ssss", $search_param, $search_param, $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Club - Estadísticas de Socios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                <a href="active_partners.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">group</span>
                    <span class="ml-3">Socios Activos</span>
                </a>
                <a href="search_partner.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
                    <span class="material-icons">search</span>
                    <span class="ml-3">Buscar Socio</span>
                </a>
                <a href="register_partner.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">person_add</span>
                    <span class="ml-3">Registrar Socio</span>
                </a>
                <a href="payment_control.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">payment</span>
                    <span class="ml-3">Control de Pagos</span>
                </a>
                <a href="payment_history.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">history</span>
                    <span class="ml-3">Historial de Movimientos</span>
                </a>
                <a href="profile.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">mail</span>
                    <span class="ml-3">Alertas</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">account_circle</span>
                    <span class="ml-3">Usuarios</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">settings</span>
                    <span class="ml-3">Ajustes</span>
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
            <main class="flex-1 overflow-y-auto p-4">
            <!-- Search Form -->
            <div class="p-6">
                <h2 class="text-3xl font-semibold mb-6">Buscar Socio</h2>
                <form action="search_partner.php" method="GET" class="bg-gray-800 p-6 rounded-lg shadow-md">
                    <input type="text" name="search_term" placeholder="Buscar por nombre, apellido, cédula o acción" 
                           class="bg-gray-700 text-gray-200 p-4 w-full mb-4 rounded-lg border border-gray-600 focus:outline-none focus:border-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg w-full">
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Results Section -->
            <div class="p-6">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table-auto w-full bg-gray-800 text-gray-100 rounded-lg shadow-lg">
                        <thead>
                            <tr class="bg-gray-700">
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Apellido</th>
                                <th class="px-4 py-2">Cédula</th>
                                <th class="px-4 py-2">Número</th>
                                <th class="px-4 py-2">Correo</th>
                                <th class="px-4 py-2">Acción</th>
                                <th class="px-4 py-2">Estado</th>
                                <th class="px-4 py-2">Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="bg-gray-800 hover:bg-gray-700">
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['apellido']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['cedula']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['numero']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['correo']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['accion']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['estado']); ?></td>
                                    <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['vencimiento']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-red-500">No se encontraron resultados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
