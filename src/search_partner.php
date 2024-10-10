<?php
include '../backend/db_connection.php';

// Inicializa el término de búsqueda vacío
$search_term = '';
$search_success = null; // Variable para almacenar el éxito o fracaso de la búsqueda
if (isset($_GET['search_term'])) {
    $search_term = $_GET['search_term'];

    // Consulta SQL para buscar coincidencias
    $query = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo, cedula, numero, correo, accion, estado, vencimiento FROM socios WHERE 
          nombre LIKE ? OR 
          apellido LIKE ? OR 
          cedula = ? OR 
          accion = ?";

    $stmt = $conn->prepare($query);
    $search_param = "%" . $search_term . "%";
    $stmt->bind_param("ssss", $search_param, $search_param, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si hay resultados, la búsqueda tuvo éxito
    if ($result->num_rows > 0) {
        $search_success = true;
    } else {
        $search_success = false;
    }
}
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
    <script src="../js/search_partner.js"></script>

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
                <div class="flex justify-center items-center">
                    <div class="p-6 bg-gray-800 rounded-lg shadow-md w-full max-w-lg">
                        <h2 class="text-3xl font-semibold mb-6 text-center">Buscar Socio</h2>
                        <form action="search_partner.php" method="GET" class="flex flex-col items-center space-y-4" onsubmit="return validateSearch()">
                            <div class="relative w-full">
                                <label for="search_term" class="block mb-2 text-sm font-medium text-gray-300">Buscar</label>
                                <input type="text" id="search_term" name="search_term"
                                    value="<?php echo htmlspecialchars($search_term); ?>" 
                                    class="bg-gray-700 border border-gray-500 text-white text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-3 pl-10" 
                                    placeholder="Ingrese cédula, nombre, apellido o acción">
                                
                            </div>

                            <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white p-3 rounded-lg w-full flex justify-center items-center space-x-2">
                                <span class="material-icons">search</span>
                                <span>Buscar</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Results Section -->
                <div class="p-6 text-center">
                    <?php if ($search_term): ?>
                        <?php if ($search_success): ?>
                            <p class="text-green-500">Socio encontrado.</p>
                            <table class="table-auto w-full bg-gray-800 text-gray-100 rounded-lg shadow-lg mt-4">
                            <thead>
    <tr class="bg-gray-700">
        <th class="px-4 py-2">Nombre Completo</th>
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
            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
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
                            <p class="text-red-500">No se encontraron resultados para "<?php echo htmlspecialchars($search_term); ?>"</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>