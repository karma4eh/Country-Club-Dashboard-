<?php
include '../backend/db_connection.php';
session_start();
include_once '../backend/verificar_seccion.php';
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
                <a href="active_partners.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
                    <span class="material-icons">group</span>
                    <span class="ml-3">Socios Activos</span>
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
                <!-- Display de datos -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
                        <h2 class="text-lg font-semibold">Socios Activos</h2>
                        <p id="total_activos" class="text-3xl mt-2">Cargando...</p>
                    </div>
                    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
                        <h2 class="text-lg font-semibold">Socios Rematados</h2>
                        <p id="total_inactivos" class="text-3xl mt-2">Cargando...</p>
                    </div>
                </div>

                <!-- Gráfico -->
                <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold mb-2 text-center">Gráfico</h2>
                    <div class="w-full flex justify-center" style="height: 300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <script>
                    // Llamada AJAX
                    $.getJSON('../backend/get_partner_stats.php', function(data) {
                        // Mostrar los totales
                        $('#total_activos').text(data.total_activos);
                        $('#total_inactivos').text(data.total_inactivos);

                        //  gráfico con Chart.js
                        const ctx = document.getElementById('myChart').getContext('2d');
                        const myChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ['Activos', 'Inactivos'],
                                datasets: [{
                                    data: [data.total_activos, data.total_inactivos],
                                    backgroundColor: ['#4CAF50', '#F44336'],
                                    borderColor: '#111827',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#FFFFFF'
                                        },
                                        position: 'bottom'
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            color: '#FFFFFF'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#FFFFFF'
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

            </main>
        </div>
    </div>

</body>
</html>