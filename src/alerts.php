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
    <title>Country Club Alertas</title>
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
                <a href="#" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
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
<!-- Alertas Section -->
<main class="flex-1 p-6">
    
    <div class="bg-gray-800 p-4 rounded-lg shadow-lg max-w-md mx-auto">
        <p class="text-gray-400 mb-4 text-center">Selecciona una opción y redacta el mensaje para los socios:</p>

        <!-- Formulario para enviar alertas por correo -->
        <form action="send_mail.php" method="POST" class="space-y-4">
            <!-- Tipo de Alerta -->
            <div>
                <label class="block text-gray-300 mb-1" for="alert-type">Enviar a</label>
                <select name="alert_type" id="alert-type" class="w-full p-2 bg-gray-700 text-gray-100 border border-gray-600 rounded focus:outline-none focus:ring focus:ring-blue-500">
                    <option value="morosos">Morosos</option>
                    <option value="todos">Todos los Socios</option>
                    <option value="especifico">Socio Específico</option>
                </select>
            </div>

            <!-- Cédula del Socio Específico -->
            <div id="specific-member" class="hidden">
                <label class="block text-gray-300 mb-1" for="member-id">Cédula del Socio</label>
                <input type="text" name="member_id" id="member-id" placeholder="Ingrese la cédula" class="w-full p-2 bg-gray-700 text-gray-100 border border-gray-600 rounded focus:outline-none focus:ring focus:ring-blue-500">
            </div>

            <!-- Asunto del Correo -->
            <div>
                <label class="block text-gray-300 mb-1" for="subject">Asunto</label>
                <input type="text" name="subject" id="subject" placeholder="Asunto del mensaje" class="w-full p-2 bg-gray-700 text-gray-100 border border-gray-600 rounded focus:outline-none focus:ring focus:ring-blue-500">
            </div>

            <!-- Cuerpo del Mensaje -->
            <div>
                <label class="block text-gray-300 mb-1" for="message">Mensaje</label>
                <textarea name="message" id="message" rows="4" placeholder="Escribe tu mensaje aquí" class="w-full p-2 bg-gray-700 text-gray-100 border border-gray-600 rounded focus:outline-none focus:ring focus:ring-blue-500"></textarea>
            </div>

            <!-- Botones para enviar o cancelar -->
            <div class="flex justify-end space-x-2">
                <button type="reset" class="px-3 py-2 bg-gray-600 text-gray-100 rounded hover:bg-gray-700 focus:outline-none focus:ring focus:ring-blue-500">Cancelar</button>
                <button type="submit" class="px-3 py-2 bg-blue-600 text-gray-100 rounded hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-500">Enviar</button>
            </div>
        </form>
    </div>
</main>

<script>
    // Mostrar campo para un socio específico solo cuando se selecciona "Socio Específico"
    document.getElementById('alert-type').addEventListener('change', function() {
        const specificMemberField = document.getElementById('specific-member');
        specificMemberField.classList.toggle('hidden', this.value !== 'especifico');
    });
</script>

            
            </html>