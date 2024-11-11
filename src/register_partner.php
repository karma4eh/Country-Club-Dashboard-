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
            <a href="payment_control.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">payment</span>
                <span class="ml-3">Control de Pagos</span>
            </a>
            <a href="movements.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">history</span>
                <span class="ml-3">Historial de Movimientos</span>
            </a>
            <a href="alerts.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">mail</span>
                <span class="ml-3">Alertas</span>
            </a>
            <a href="#" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">account_circle</span>
                <span class="ml-3">Usuarios</span>
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
            <!-- Contenedor del formulario -->
            <div class="max-w-lg mx-auto bg-gray-800 shadow-md rounded-lg p-4 text-white">
                <form action="../backend/register_socio.php" method="POST" class="w-full" id="registerForm">
                    <!-- Nombres y Apellidos -->
                    <div class="flex flex-wrap mb-3">
                        <div class="w-1/2 pr-2">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="nombre">
                                <span class="material-icons">person</span> Nombres
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="nombre" name="nombre" type="text" placeholder="Maria" required>
                        </div>
                        <div class="w-1/2 pl-2">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="apellido">
                                <span class="material-icons">person</span> Apellidos
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="apellido" name="apellido" type="text" placeholder="Rodriguez" required>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="direccion">
                            <span class="material-icons">home</span> Dirección
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="direccion" name="direccion" type="text" placeholder="Calle, sector, ciudad" required>
                    </div>

                    <!-- Cédula y Teléfono -->
                    <div class="flex flex-wrap mb-3">
                        <div class="w-1/2 pr-2">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="cedula">
                                <span class="material-icons">badge</span> Cédula
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="cedula" name="cedula" type="text" placeholder="Ingresa la cédula" required>
                        </div>
                        <div class="w-1/2 pl-2">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="numero">
                                <span class="material-icons">phone</span> Teléfono
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="numero" name="numero" type="tel" placeholder="Ingresa el número" required>
                        </div>
                    </div>

                    <!-- Acción del socio y correo -->
                    <div class="mb-3">
                        <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="accion_socio">
                            <span class="material-icons">assignment</span> Acción del Socio
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="accion_socio" name="accion" type="text" placeholder="Ingresa la acción del socio" required>
                    </div>
                    <div class="mb-3">
                        <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="correo">
                            <span class="material-icons">email</span> Correo Electrónico
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" id="correo" name="correo" type="email" placeholder="Ingresa el correo" required>
                    </div>

                    <!-- Estado del socio -->
                    <div class="mb-3">
                        <label class="block uppercase tracking-wide text-white text-xs font-bold mb-1" for="estado">
                            <span class="material-icons">toggle_on</span> Estado del Socio
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border text-gray-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white" id="estado" name="estado" required>
                            <option value="" disabled selected>Selecciona el estado</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Mensaje de respuesta -->
                    <div id="response-message" class="hidden p-2 mt-2 text-sm"></div>
                    <div class="flex justify-center">
                        <button id="submit-btn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Registrar Socio
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<script>
    const form = document.getElementById('registerForm');
const responseMessage = document.getElementById('response-message');

form.addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

    const formData = new FormData(form);

    try {
        const response = await fetch('../backend/register_socio.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        // Mostrar el mensaje según la respuesta del servidor
        if (result.success) {
            responseMessage.textContent = result.success;
            responseMessage.classList.remove('hidden');
            responseMessage.classList.add('bg-green-500', 'text-white');
        } else if (result.error) {
            responseMessage.textContent = result.error;
            responseMessage.classList.remove('hidden');
            responseMessage.classList.add('bg-red-500', 'text-white');
        }
    } catch (error) {
        responseMessage.textContent = "Hubo un error al procesar el formulario.";
        responseMessage.classList.remove('hidden');
        responseMessage.classList.add('bg-red-500', 'text-white');
    }
});

</script>
<script>
    // Validación de formulario
    const form = document.getElementById('registerForm');

    form.addEventListener('submit', function(event) {
        let valid = true;

        // Validación para el campo "estado"
        const estado = document.getElementById('estado');
        if (!estado.value) {
            document.getElementById('estado-error').classList.remove('hidden');
            valid = false;
        } else {
            document.getElementById('estado-error').classList.add('hidden');
        }

        if (!valid) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>

