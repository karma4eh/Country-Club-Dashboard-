<?php
include_once '../backend/count_socios_activos.php';
include '../backend/db_connection.php';
session_start();

// Verifica si el usuario ha iniciado sesión
include_once '../backend/verificar_seccion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Club Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="../js/search_socios.js"></script>
    <script src="../js/ver_socios.js"></script>
</head>
<body class="bg-gray-900 text-gray-100">

<!-- Modal -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <!-- Botón "X" para cerrar el modal -->
        <button onclick="cerrarModal()" class="absolute top-2 right-2 text-gray-300 hover:text-white text-2xl">&times;</button>
        

        <!-- Contenido del Modal -->
        <div id="modal-content" class="text-gray-200 bg-gray-700 p-4 rounded-lg mb-4">
            <!-- Aquí irán los datos específicos del socio -->
        </div>

        <!-- Botón Cerrar en la parte inferior -->
        <div class="flex justify-center mt-4">
            <button class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500" onclick="cerrarModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    function cerrarModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>

    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-gray-800 shadow-lg">
            
            <nav class="mt-10">
                <a href="dashboard.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
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
                <a href="movements.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
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
                    <!-- Logo del Club -->
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
           <h2 class="text-2xl font-semibold text-gray-100 mb-4 flex items-center">
    Socios Activos: <span class="text-yellow-500 ml-2"><?php echo $total_activos; ?></span>

    <form id="searchForm" class="ml-auto flex items-center space-x-2">
    <input type="text" id="search" name="search" placeholder="Ingresa nombre, apellido, cédula o acción" 
           class="bg-gray-700 border border-gray-600 text-gray-300 placeholder-gray-400 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-2.5 w-80">
    
           <button type="submit" id="searchButton" class="bg-yellow-500 text-gray-900 p-2 rounded-lg hover:bg-yellow-600 focus:outline-none flex items-center justify-center" style="height: 40px; width: 40px;">
    <span class="material-icons text-base">search</span>
</button>

</form>
</h2>



                <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg p-4">
                    <table class="min-w-full bg-gray-900">
                        <thead>
                            <tr>
                                
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Nombre Completo</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Cédula</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Acción</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Estado</th>
                                <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Saldo</th>
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
                <a href="active_partners.php">
                <button class='px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700'>Ver más</button>
                 </a>

                </div>

            <!-- Payment History Section -->
<div class="mt-8">
    <h2 class="text-2xl font-semibold text-gray-100 mb-4">Historial de Pagos</h2>
    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg p-4">
        <table class="min-w-full bg-gray-900">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Socio</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Descripción</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Monto</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Fecha de Pago</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm font-semibold text-gray-400">Factura</th>
                </tr>
            </thead>
            <tbody>
            <?php include '../backend/get_pagos.php'; ?>
            </tbody>
        </table>
    </div>
</div>


            </main>
        </div>
    </div>

</body>
</html>
