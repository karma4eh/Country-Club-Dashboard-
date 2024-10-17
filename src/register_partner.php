<?php
include_once '../backend/count_socios_activos.php';
include '../backend/db_connection.php';
session_start();
include_once '../backend/verificar_seccion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Socio - Country Club</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            <a href="register_partner.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
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
            <div class="max-w-md mx-auto bg-gray-800 shadow-md rounded-lg p-6 text-white">
                <h2 class="text-2xl font-semibold mb-6 text-center">Registro de Socios</h2>

                <!-- Formulario de inputs -->
                <form action="../backend/register_socio.php" method="POST">
                    <div class="mb-4">
                        <label for="nombre" class="block font-medium mb-2">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa el nombre" required>
                    </div>

                    <div class="mb-4">
                        <label for="apellido" class="block font-medium mb-2">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa el apellido" required>
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="block font-medium mb-2">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa la dirección" required>
                    </div>

                    <div class="mb-4">
                        <label for="cedula" class="block font-medium mb-2">Cédula:</label>
                        <input type="text" id="cedula" name="cedula" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa la cédula" required>
                    </div>

                    <div class="mb-4">
                        <label for="numero" class="block font-medium mb-2">Teléfono:</label>
                        <input type="tel" id="numero" name="numero" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa el número" required>
                    </div>

                    <div class="mb-4">
                        <label for="correo" class="block font-medium mb-2">Correo Electrónico:</label>
                        <input type="email" id="correo" name="correo" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa el correo" required>
                    </div>

                    <div class="mb-4">
                        <label for="accion" class="block font-medium mb-2">Acción:</label>
                        <input type="text" id="accion" name="accion" 
                               class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Ingresa la acción" required>
                    </div>

                    <div class="mb-4">
                        <label for="estado" class="block font-medium mb-2">Estado:</label>
                        <select id="estado" name="estado" 
                                class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Botón para enviar el formulario -->
                    <div class="flex justify-center">
                        <button type="submit" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Registrar Socio
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

</body>
</html>

