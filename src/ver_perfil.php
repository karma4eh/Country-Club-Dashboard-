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
                    <a href="#" id="abrir-editar" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                        <span class="material-icons mr-2">edit</span>Editar Datos
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
                <label for="input-estado" class="text-gray-300">Estado</label>
                <select id="input-estado" class="w-full p-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div>
                <label for="input-direccion" class="text-gray-300">Dirección</label>
                <input type="text" id="input-direccion" class="w-full p-2 rounded-md bg-gray-700 text-gray-100" />
            </div>

            <button type="submit" class="w-full bg-yellow-400 text-gray-900 p-2 rounded-md mt-4 hover:bg-yellow-500">Actualizar</button>
        </form>
    </div>
</div>
<!-- Modal para el historial de pagos -->
<div id="modal-historial-pagos" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl text-yellow-400">Historial de Pagos</h3>
            <button id="cerrar-modal-historial" class="text-gray-500 hover:text-gray-300">
                <span class="material-icons">close</span>
            </button>
        </div>

        <div class="mt-4 space-y-4" id="historial-pagos">
            <!-- Los pagos se llenarán con JavaScript -->
        </div>
    </div>
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

        // Llenar los datos del socio en el perfil
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

    // Abrir el modal para editar datos
    $('a[href="#"]').click(function(e) {
        e.preventDefault();
        $('#modal-editar').removeClass('hidden');

        // Llamamos a la función llenarModal para cargar los datos actuales del socio
        $.getJSON(`../backend/get_socio.php?cedula=${cedula}`, function(data) {
            llenarModal(data); // Asegúrate de que 'data' contiene los datos del socio
        });
    });

    // Abrir el modal para historial de pagos
    $('#abrir-historial').click(function(e) {
        e.preventDefault();
        
        // Cerrar el modal de editar perfil si está abierto
        $('#modal-editar').addClass('hidden');
        
        // Abrir el modal de historial de pagos
        $('#modal-historial-pagos').removeClass('hidden');

        // Aquí agregarás la lógica para cargar el historial de pagos, si es necesario
        $.getJSON(`../backend/get_historial_pagos.php?socio_id=${cedula}`, function(pagos) {
            console.log(pagos);
            // Suponiendo que los datos de pagos se devuelven en el objeto 'pagos'
            let historialHtml = '';
            pagos.forEach(pago => {
                historialHtml += `
                    <div class="flex justify-between items-center">
                        <p class="text-gray-300"><strong>Fecha:</strong> ${new Date(pago.fecha).toLocaleDateString()}</p>
                        <p class="text-gray-300"><strong>Monto:</strong> ${pago.monto}</p>
                    </div>
                `;
            });
            $('#historial-pagos').html(historialHtml);
        });
    });

    // Cerrar el modal de editar perfil
    $('#cerrar-modal').click(function() {
        $('#modal-editar').addClass('hidden');
    });

    // Cerrar el modal de historial de pagos
    $('#cerrar-modal-historial').click(function() {
        $('#modal-historial-pagos').addClass('hidden');
    });

    // Función para llenar los campos del modal con los datos actuales del socio
    function llenarModal(data) {
        $('#input-nombre').val(data.nombre);
        $('#input-apellido').val(data.apellido);
        $('#input-correo').val(data.correo);
        $('#input-numero').val(data.numero);
        $('#input-direccion').val(data.direccion);

        // Asignar el valor de 'estado' al select correspondiente
        if (data.estado == 'activo') {
            $('#input-estado').val('activo');
        } else if (data.estado == 'inactivo') {
            $('#input-estado').val('inactivo');
        } else {
            $('#input-estado').val(''); // Si el estado no es reconocido, dejarlo en blanco
        }
    }

    // Actualizar los datos del socio
    $('#form-editar').submit(function(e) {
        e.preventDefault();

        const datosActualizados = {
            nombre: $('#input-nombre').val(),
            apellido: $('#input-apellido').val(),
            correo: $('#input-correo').val(),
            numero: $('#input-numero').val(),
            estado: $('#input-estado').val(),
            direccion: $('#input-direccion').val(),
            cedula: cedula // Pasamos la cédula al backend
        };

        // Filtrar solo los campos que tienen valores para no enviar valores vacíos
        for (let key in datosActualizados) {
            if (datosActualizados[key] === '') {
                delete datosActualizados[key];
            }
        }

        // Hacer la solicitud al backend
        $.ajax({
            url: '../backend/actualizar_socio.php',
            method: 'POST',
            data: datosActualizados,
            success: function(response) {
    if (response.success) {
        alert('Datos actualizados con éxito');
        $('#modal-editar').addClass('hidden');
        location.reload(); // Recargar los datos del socio
    } else {
         //ESTO HAY QUE ARREGLARLO ESTE MENSAJE ES EL DE
        location.reload();
    }
},
error: function() {
    alert('Hubo un error en la conexión');
},
            error: function() {
                alert('Hubo un error en la conexión');
            }
        });
    });
});

</script>


</body>
</html>
