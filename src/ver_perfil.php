<?php
include '../backend/db_connection.php';
session_start();
include '../backend/verificar_seccion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Socio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-900 text-gray-100">

<div class="min-h-screen flex flex-col items-center pt-8 px-4">
    <!-- Header -->
    <header class="flex items-center justify-between w-full max-w-4xl bg-gray-800 p-6 rounded-lg shadow-lg mb-8">
        <div class="flex items-center">
            <button onclick="window.history.back();" class="text-gray-300 hover:text-gray-100 text-2xl">
                <span class="material-icons">arrow_back</span>
            </button>
            <img src="../img/logo.png" alt="Logo Country Club" class="h-10 ml-4">
            <h1 class="text-2xl font-bold text-gray-100 ml-4">Country Club</h1>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-gray-400">Bienvenido, Admin</span>
            <a href="../backend/logout.php" class="text-red-500 flex items-center space-x-1">
                <span class="material-icons">logout</span>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="w-full max-w-4xl bg-gray-800 p-8 rounded-lg shadow-lg">
        <!-- Perfil del Usuario -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-yellow-400" id="socio-nombre">Perfil del Socio</h2>
            <img src="" alt="Foto de perfil" id="socio-imagen" class="rounded-full h-24 w-24 border-4 border-gray-700 shadow-lg">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-900 p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold mb-4 text-green-400">Datos Personales</h3>
                <div class="space-y-3" id="socio-datos">
                    <!-- Los datos del socio se llenarán con JavaScript -->
                </div>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold mb-4 text-green-400">Opciones</h3>
                <div class="space-y-4">
                    <a href="#" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                        <span class="material-icons mr-2">edit</span>Editar Datos
                    </a>
                    <a href="#" class="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                        <span class="material-icons mr-2">history</span>Historial de Pagos
                    </a>
                </div>
            </div>
        </div>

        <!-- Calendario de Visitas -->
        <div class="bg-gray-900 p-6 rounded-lg shadow-md mt-8 w-full max-w-4xl">
            <h3 class="text-2xl font-semibold text-yellow-400 mb-4">Visitas del mes</h3>
            <div class="grid grid-cols-7 gap-2 justify-items-center" id="calendario-visitas">
                <!-- Los días con visitas se llenarán con JavaScript -->
            </div>
        </div>
    </main>
</div>

<script>
    $(document).ready(function() {
        // Obtener el parámetro de cédula desde la URL
        const cedula = new URLSearchParams(window.location.search).get('cedula');

        // Cargar datos del socio
        $.getJSON(`../backend/get_socio.php?cedula=${cedula}`, function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Mostrar el nombre del socio y la foto de perfil
            $('#socio-nombre').text(`${data.nombre} ${data.apellido}`);
            $('#socio-imagen').attr('src', data.imagen_perfil);

            // Llenar los datos del socio
            $('#socio-datos').html(`
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">account_circle</span>
                    <p><strong>Nombre:</strong> ${data.nombre} ${data.apellido}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">badge</span>
                    <p><strong>Cédula:</strong> V-${data.cedula}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">mail</span>
                    <p><strong>Correo:</strong> ${data.correo}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">phone</span>
                    <p><strong>Teléfono:</strong> ${data.numero}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">check_circle</span>
                    <p><strong>Acción:</strong> ${data.accion}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">info</span>
                    <p><strong>Estado:</strong> ${data.estado}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">calendar_today</span>
                    <p><strong>Socio Desde:</strong> ${data.socio_desde ? new Date(data.socio_desde).toLocaleDateString() : 'No disponible'}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-yellow-400">home</span>
                    <p><strong>Dirección:</strong> ${data.direccion}</p>
                </div>
            `);

            // Cargar las visitas del socio
            $.getJSON(`../backend/get_visitas.php?socio_id=${data.id}`, function(visitas) {
                const visitasDias = visitas.map(v => parseInt(v)); // Los días de visita
                let calendarioHtml = '';

                // Crear los días del calendario
                for (let i = 1; i <= 30; i++) {
                    const esVisita = visitasDias.includes(i); // Verifica si el día tiene visita
                    calendarioHtml += `
                        <div class="w-full h-12 flex justify-center items-center ${esVisita ? 'bg-green-600 text-white border-2 border-yellow-400' : 'bg-gray-700 text-white border border-gray-800'}">
                            ${i}
                        </div>
                    `;
                }

                // Insertar el calendario generado
                $('#calendario-visitas').html(calendarioHtml);
            });
        });
    });
</script>


</body>
</html>
