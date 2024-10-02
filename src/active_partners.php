<?php
include '../backend/db_connection.php';

// Consulta para contar socios activos
$sql_activos = "SELECT COUNT(*) AS total_activos FROM socios WHERE estado = 'activo'";
$result_activos = $conn->query($sql_activos);
$total_activos = $result_activos->fetch_assoc()['total_activos'];

// Consulta para contar socios inactivos
$sql_inactivos = "SELECT COUNT(*) AS total_inactivos FROM socios WHERE estado = 'inactivo'";
$result_inactivos = $conn->query($sql_inactivos);
$total_inactivos = $result_inactivos->fetch_assoc()['total_inactivos'];

$conn->close();
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
                <a href="active_partners.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
                    <span class="material-icons">group</span>
                    <span class="ml-3">Socios Activos</span>
                </a>
                <a href="search_partner.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
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
                <img src="../img/logo.png" alt="Logo Country Club" class="h-9 mr-4">
                <h1 class="text-xl font-bold text-gray-100">Country Club</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-400">Bienvenido, Admin</span>
                <a href="../backend/logout.php" class="text-red-500">Cerrar Sesión</a>
                <span class="material-icons">logout</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Display de datos -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold">Socios Activos</h2>
                    <p class="text-4xl mt-4"><?php echo $total_activos; ?></p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold">Socios Inactivos</h2>
                    <p class="text-4xl mt-4"><?php echo $total_inactivos; ?></p>
                </div>
            </div>

            <!-- Gráfico -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Distribución de Socios</h2>
                <canvas id="myChart" width="400" height="150"></canvas>
            </div>

            <!-- Tabla de Socios Activos -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-100 mb-4">Lista de Socios Activos</h2>
                <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg p-4">
                    <table class="min-w-full bg-gray-900">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Nombre Completo</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Cédula</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Estado</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../backend/get_socios.php'; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Load more button -->
                <div class="mt-4 text-center">
                    <a href="active_partners.php">
                        <button class='px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700'>Ver más</button>
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // Datos desde PHP
    const totalActivos = <?php echo $total_activos; ?>;
    const totalInactivos = <?php echo $total_inactivos; ?>;

    // Crear gráfico con Chart.js
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Activos', 'Inactivos'],
            datasets: [{
                data: [totalActivos, totalInactivos],
                backgroundColor: ['#4CAF50', '#F44336'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>

</body>
</html>
