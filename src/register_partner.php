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
            <a href="payment_control.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
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

                <form action="../backend/register_socio.php" method="POST" class="w-full max-w-lg" id="registerForm">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="nombre">
                                Nombres
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="nombre" name="nombre" type="text" placeholder="Maria" required>
                            <p class="text-red-500 text-xs italic hidden" id="nombre-error">Por favor complete este campo.</p>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="apellido">
                                Apellidos
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="apellido" name="apellido" type="text" placeholder="Rodriguez" required>
                            <p class="text-red-500 text-xs italic hidden" id="apellido-error">Por favor complete este campo.</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="direccion">
                                Dirección
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="direccion" name="direccion" type="text" placeholder="Calle, carrera, sector, ciudad, código postal" required>
                            <p class="text-red-500 text-xs italic hidden" id="direccion-error">Por favor complete este campo.</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="cedula">
                                Cédula
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="cedula" name="cedula" type="text" placeholder="Ingresa la cédula" required>
                            <p class="text-red-500 text-xs italic hidden" id="cedula-error">Por favor complete este campo.</p>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="numero">
                                Teléfono
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="numero" name="numero" type="tel" placeholder="Ingresa el número" required>
                            <p class="text-red-500 text-xs italic hidden" id="numero-error">Por favor complete este campo.</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="accion_socio">
                                Acción del socio
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="accion_socio" name="accion" type="text" placeholder="Ingresa la acción del socio" required>
                            <p class="text-red-500 text-xs italic hidden" id="accion-error">Por favor complete este campo.</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="correo">
                                Correo Electrónico
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="correo" name="correo" type="email" placeholder="Ingresa el correo" required>
                            <p class="text-red-500 text-xs italic hidden" id="correo-error">Por favor complete este campo.</p>
                        </div>
                    </div>

<div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3">
            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="estado">
                Estado del Socio
            </label>
            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="estado" name="estado" required>
                <option value="" disabled selected>Selecciona el estado</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
            <p class="text-red-500 text-xs italic hidden" id="estado-error">Por favor seleccione el estado.</p>
        </div>
    </div>

    <div id="response-message" class="hidden p-4 mt-4 text-sm"></div>
    <div class="flex justify-center">
        <button id="submit-btn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Registrar Socio
        </button>
    </div>
</form>
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

