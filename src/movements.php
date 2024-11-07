<?php
include '../backend/db_connection.php';
session_start();
include '../backend/verificar_seccion.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos - Country Club</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="../js/modalpayment.js"></script>
</head>
<body class="bg-gray-900 text-gray-100">
<!-- Sidebar -->
<div class="flex h-screen">
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
            <a href="register_partner.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">person_add</span>
                <span class="ml-3">Registrar Socio</span>
            </a>
            <a href="payment_control.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">payment</span>
                <span class="ml-3">Control de Pagos</span>
            </a>
            <a href="movements.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
                <span class="material-icons">history</span>
                <span class="ml-3">Historial de Movimientos</span>
            </a>
            <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
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

        <!-- Tabla de movimientos -->
        <div class="flex-grow flex items-center justify-center mt-10">
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-5xl">
                <h2 class="text-2xl font-bold mb-4 text-center text-white">Historial de Movimientos</h2>
                <!-- Cargando Spinner -->
                <div id="loading-spinner" class="flex justify-center items-center text-white mb-4">
                    <div class="spinner-border animate-spin h-8 w-8 border-t-4 border-white rounded-full"></div>
                    <span class="ml-2">Cargando...</span>
                </div>
                <!-- Tabla de movimientos (inicialmente oculta) -->
                <table id="movimientos-table" class="min-w-full table-auto text-left text-sm text-gray-400 hidden">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Usuario</th>
                            <th class="px-4 py-2">Tipo</th>
                            <th class="px-4 py-2">Rol</th> <!-- Nueva columna para Rol -->
                        </tr>
                    </thead>
                    <tbody id="movimientos-table-body">
                        <!-- Aquí se insertarán los movimientos desde JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Promesa que asegura un tiempo mínimo de 3 segundos antes de mostrar la tabla
const minLoadTime = new Promise(resolve => setTimeout(resolve, 3000));

// Obtener movimientos y mostrarlos en la tabla
const fetchData = fetch('../backend/get_movimientos.php')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('movimientos-table-body');

        if (data.length > 0) {
            data.forEach(movimiento => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-700');

                row.innerHTML = `
                    <td class="px-4 py-2">${movimiento.fecha}</td>
                    <td class="px-4 py-2">${movimiento.usuario}</td>
                    <td class="px-4 py-2">${movimiento.tipo}</td>
                    <td class="px-4 py-2">${movimiento.rol}</td> <!-- Mostrar rol -->
                `;

                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = "<tr><td colspan='4' class='text-center py-4'>No hay movimientos registrados.</td></tr>";
        }
    })
    .catch(error => {
        console.error("Error al obtener los movimientos:", error);
        const tableBody = document.getElementById('movimientos-table-body');
        tableBody.innerHTML = "<tr><td colspan='4' class='text-center py-4'>Error al cargar movimientos.</td></tr>";
    });

// Ocultar el spinner y mostrar la tabla después de que ambos: datos y tiempo mínimo estén listos
Promise.all([fetchData, minLoadTime]).then(() => {
    const loadingSpinner = document.getElementById('loading-spinner');
    const table = document.getElementById('movimientos-table');

    loadingSpinner.style.display = 'none';
    table.classList.remove('hidden');
});
</script>

</body>
</html> 