<?php
include '../backend/db_connection.php';
session_start();
include '../backend/verificar_seccion.php';
include '../backend/bcv_rate.php';

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
            <a href="register_partner.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                <span class="material-icons">person_add</span>
                <span class="ml-3">Registrar Socio</span>
            </a>
            <a href="payment_control.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
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
       <!-- Contenedor centrado -->
       <div class="flex-grow flex items-center justify-center mt-10"> <!-- Agregar mt-10 para separar del header -->
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-2xl flex">
                <!-- Formulario de pago -->
                <div class="w-1/2 pr-6">
                    <h2 class="text-2xl font-bold mb-4">Registrar Pago</h2>
                    <form id="paymentForm" class="space-y-4">
                        <div>
                            <label for="cedula" class="block text-sm">Cédula del Socio</label>
                            <input type="text" id="cedula" name="cedula" required class="w-full px-3 py-2 bg-gray-700 text-white rounded" placeholder="Ingrese la cédula del socio">
                        </div>

                        <div>
                            <label for="monto_dolares" class="block text-sm">Monto en Dólares</label>
                            <input type="number" id="monto_dolares" name="monto_dolares" step="0.01" required class="w-full px-3 py-2 bg-gray-700 text-white rounded" placeholder="Ingrese el monto en dólares" oninput="calcularBolivares()">
                        </div>

                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Registrar Pago</button>
                    </form>
                </div>

                <!-- Div de la factura -->
                <div class="w-1/2 bg-gray-700 p-6 rounded-lg shadow-lg">
                    

                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-white-400">Nombre del Socio:</span>
                        <span id="nombre_socio" class="text-white">N/A</span> <!-- Aquí se mostrará el nombre del socio -->
                    </div>

                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-white-400">Deuda del Socio:</span>
                        <span id="deuda_socio" class="text-white">N/A</span> <!-- Aquí se mostrará la deuda del socio -->
                    </div>

                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-white-400">Tasa BCV:</span>
                        <span id="tasa_bcv" class="text-yellow-500">0.00</span> <!-- Aquí se mostrará la tasa actual del BCV -->
                    </div>

                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-white-400">Monto en Bolívares:</span>
                        <span id="monto_bolivares" class="text-green-500">0.00</span> <!-- Aquí se calculará el monto en bolívares -->
                    </div>

                    <div class="flex justify-center mt-6">
                        <img src="../img/Banco_Central_de_Venezuela_logo.svg" alt="Logo BCV" class="h-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Obtener la tasa BCV desde PHP
    const tasaBCV = <?php echo obtenerTasaDolarBCV(); ?>;

    // Mostrar la tasa del BCV en el div
    document.getElementById('tasa_bcv').textContent = tasaBCV.toFixed(2);

    // Función para calcular el monto en bolívares
    function calcularBolivares() {
        const montoDolares = document.getElementById('monto_dolares').value;
        const montoBolivares = montoDolares * tasaBCV;
        document.getElementById('monto_bolivares').textContent = montoBolivares.toFixed(2);
    }
</script>

</body>
</html>