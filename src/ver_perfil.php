<?php
include '../backend/db_connection.php';
session_start();
include '../backend/verificar_seccion.php';

// Verificar si la cédula está en la URL
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];

    // Preparar y ejecutar la consulta para obtener el ID del socio
    $query = "SELECT id FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cedula); // La cédula se pasa como string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $socio_id = $row['id'];
    } else {
        echo "Error: No se encontró un socio con esa cédula.";
        exit;
    }

    $stmt->close();
} else {
    echo "Error: Parámetro de cédula no proporcionado en la URL.";
    exit;
}
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
    <script src="../js/ver_perfil.js"></script>
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
                    <a href="#" id="abrir-editar" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                        <span class="material-icons mr-2">edit</span>Editar Datos
                    </a>
                    <a href="" id="abrir-historial" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                     <span class="material-icons mr-2">history</span>Historial de pagos
                    </a>
                </div>
            </div>
        </div>
        <!-- Calendario de Visitas -->
        <div class="bg-gray-900 p-6 rounded-lg shadow-md mt-8 w-full max-w-4xl">
            <h3 class="text-2xl font-semibold text-yellow-400 mb-4">Visitas del mes</h3>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-3 bg-gray-700">
                    <button id="prevMonth" class="text-white">Anterior</button>
                    <h2 id="currentMonth" class="text-white"></h2>
                    <button id="nextMonth" class="text-white">Siguiente</button>
                </div>
                <div class="grid grid-cols-7 gap-2 p-4" id="calendar">
                    <!-- Calendar Days Go Here -->
                </div>
            </div>
        </div>
    </main>
</div> <!-- Agregar el mensaje "REMATADO" en amarillo encima de todo -->


    </main>
</div>
<!-- Modal para editar los datos del socio -->
<div id="modal-editar" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl text-yellow-400">Editar Datos del Socio</h3>
            <button id="cerrar-modal" class="text-gray-500 hover:text-gray-300">
                <span class="material-icons">close</span>
            </button>
        </div>
          
        <form id="form-editar" class="mt-4 space-y-4">
            <div>
                <label for="input-nombre" class="text-gray-300">Nombre</label>
                <input type="text" id="input-nombre" class="w-full p-2 rounded-md bg-gray-700 text-gray-100" />
            </div>
            <div>
                <label for="input-apellido" class="text-gray-300">Apellido</label>
                <input type="text" id="input-apellido" class="w-full p-2 rounded-md bg-gray-700 text-gray-100" />
            </div>
            <div>
                <label for="input-correo" class="text-gray-300">Correo</label>
                <input type="email" id="input-correo" class="w-full p-2 rounded-md bg-gray-700 text-gray-100" />
            </div>
            <div>
                <label for="input-numero" class="text-gray-300">Número de Teléfono</label>
                <input type="text" id="input-numero" class="w-full p-2 rounded-md bg-gray-700 text-gray-100" />
            </div>
            <div>
                <label for="input-direccion" class="text-gray-300">Dirección</label>
                <input type="text" id="input-direccion" class="w-full p-2 rounded-md bg-gray-700 text-gray-100" />
            </div>

            <button type="submit" class="w-full bg-yellow-400 text-gray-900 p-2 rounded-md mt-4 hover:bg-yellow-500">Actualizar</button>
        </form>
    </div>
</div>
<div id="modal-historial-pagos" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl text-yellow-400">Historial de Pagos</h3>
            <button id="cerrar-modal-historial" class="text-gray-500 hover:text-gray-300">
                <span class="material-icons">close</span>
            </button>
        </div>
        <div class="mt-4 space-y-4" id="historial-pagos">
            <!-- Aquí se cargarán los pagos -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        
        const socioId = <?= $socio_id; ?>; // PHP inyecta el ID del socio// Asegúrate de pasar el ID correcto
    const currentDate = new Date();
    currentYear = currentDate.getFullYear();
    currentMonth = currentDate.getMonth();

    function generateCalendar(year, month, visitasDias) {
        const calendarElement = document.getElementById('calendar');
        const currentMonthElement = document.getElementById('currentMonth');

        const firstDayOfMonth = new Date(year, month, 1);
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        calendarElement.innerHTML = '';
        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        currentMonthElement.innerText = `${monthNames[month]} ${year}`;

        const firstDayOfWeek = firstDayOfMonth.getDay();
        const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

        // Crear encabezado con los días de la semana
        daysOfWeek.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'text-center font-semibold text-black'; // Letras de los días en negro
            dayElement.innerText = day;
            calendarElement.appendChild(dayElement);
        });

        // Espacios vacíos antes del primer día
        for (let i = 0; i < firstDayOfWeek; i++) {
            calendarElement.appendChild(document.createElement('div'));
        }

        // Generar los días del mes
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'text-center py-2 border border-gray-300 text-black cursor-pointer'; // Cuadros y números en negro

            // Días con visitas
            if (visitasDias.includes(day)) {
                dayElement.classList.add('bg-green-600', 'text-white', 'border-2', 'border-yellow-400');
            }

            // Día actual
            if (year === currentDate.getFullYear() && month === currentDate.getMonth() && day === currentDate.getDate()) {
                dayElement.classList.add('bg-blue-500', 'text-white');
            }

            dayElement.innerText = day;
            calendarElement.appendChild(dayElement);
        }
    }

    function loadVisits() {
    $.getJSON(`../backend/get_visitas.php?socio_id=${socioId}&month=${currentMonth + 1}&year=${currentYear}`, function (visitas) {
         
        if (visitas && visitas.length > 0) {
            const visitasDias = [...new Set(visitas.map(v => parseInt(v)))]; // Elimina duplicados
            generateCalendar(currentYear, currentMonth, visitasDias);
        } else {
            console.error("No se encontraron visitas para este socio.");
            generateCalendar(currentYear, currentMonth, []);
        }
    }).fail(function () {
        console.error("Error al obtener las visitas.");
        generateCalendar(currentYear, currentMonth, []);
    });
}


    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        loadVisits();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        loadVisits();
    });

    loadVisits();
    $('#abrir-historial').click(function (e) {
        e.preventDefault();
        $('#modal-historial-pagos').removeClass('hidden');

        // Cargar historial de pagos
        $.getJSON(`../backend/get_historial_pagos.php?socio_id=${socioId}`, function (data) {
            const historialPagosContainer = $('#historial-pagos');
            historialPagosContainer.empty(); // Limpiar contenido previo

            if (data.length > 0) {
                data.forEach(pago => {
                    historialPagosContainer.append(`
                        <div class="bg-gray-700 p-4 rounded-lg shadow-md mb-2">
                            <p><strong>Fecha de Pago:</strong> ${pago.fecha_pago}</p>
                            <p><strong>Monto:</strong> ${pago.monto} USD</p>
                        </div>
                    `);
                });
            } else {
                historialPagosContainer.html('<p class="text-red-500">No hay pagos registrados para este socio.</p>');
            }
        }).fail(function () {
            $('#historial-pagos').html('<p class="text-red-500">Error al cargar el historial de pagos.</p>');
        });
    });

    // Cerrar el modal
    $('#cerrar-modal-historial').click(function () {
        $('#modal-historial-pagos').addClass('hidden');
    });
    
});

</script>

</body>
</html>
