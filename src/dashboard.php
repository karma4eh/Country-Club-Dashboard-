<?php
include_once '../backend/verificar_seccion.php';
include_once '../backend/count_socios_activos.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Club Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body class="bg-gray-900 text-gray-100">

    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-gray-800 shadow-lg">
            <div class="h-16 flex items-center justify-center bg-gray-900 text-white text-2xl font-bold">
                Country Club
            </div>
            <nav class="mt-10">
            <a href="dashboard.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">list</span>
                    <span class="ml-3">Inicio</span>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">group</span>
                    <span class="ml-3">Socios Activos</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">search</span>
                    <span class="ml-3">Buscar Socio</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">person_add</span>
                    <span class="ml-3">Registrar Socio</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">payment</span>
                    <span class="ml-3">Control de Pagos</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">history</span>
                    <span class="ml-3">Historial de Movimientos</span>
                </a>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">mail</span>
                    <span class="ml-3">Alertas</span>
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">settings</span>
                    <span class="ml-3">Ajustes</span>
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-gray-800 shadow-lg h-16 flex items-center justify-between px-6">
                <h1 class="text-xl font-bold text-gray-100">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400">Bienvenido, Admin</span>
                    <a href="../backend/logout.php" class="text-red-500">Cerrar Sesión</a>
                    <span class="material-icons">logout</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <!-- Active Members Section -->
                <h2 class="text-2xl font-semibold text-gray-100 mb-4">Socios Activos <?php echo $total_activos; ?></h2>
                <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg p-4">
                    <table class="min-w-full bg-gray-900">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">ID</th>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Nombre Completo</th>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Cédula</th>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Acción</th>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Estado</th>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Vencimiento</th>
                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php include '../backend/get_socios.php'; ?>
        </tbody>
    </table>
</div>
                
                <!-- Load more button -->
                <div class="mt-4 text-center">
                    <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">Ver Más</button>
                </div>

                <!-- Payment History Section -->
                <div class="mt-8">
                    <h2 class="text-2xl font-semibold text-gray-100 mb-4">Historial de Pagos</h2>
                    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg p-4">
                        <table class="min-w-full bg-gray-900">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">ID Pago</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Descripción</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Fecha</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-700 text-sm">1</td>
                                    <td class="px-6 py-4 border-b border-gray-700 text-sm">Pago mensual</td>
                                    <td class="px-6 py-4 border-b border-gray-700 text-sm">20/09/2024</td>
                                    <td class="px-6 py-4 border-b border-gray-700 text-sm">
                                        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Ver Detalles</button>
                                    </td>
                                </tr>
                                <!-- Add more rows as necessary -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>
