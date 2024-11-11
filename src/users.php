<?php
include '../backend/db_connection.php';
session_start();

// Verifica si el usuario ha iniciado sesión
include_once '../backend/verificar_seccion.php';

// Conexión a la base de datos

// Consulta para obtener los usuarios
$query = "SELECT id,email, rol FROM usuarios"; // Modifica según la estructura de tu base de datos
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error al obtener los usuarios: " . mysqli_error($conn));
}
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
                <a href="alerts.php" class="flex items-center px-6 py-2 text-gray-200 hover:bg-gray-700">
                    <span class="material-icons">mail</span>
                    <span class="ml-3">Alertas</span>
                </a>
                <a href="users.php" class="flex items-center px-6 py-2 text-gray-200 bg-gray-700">
                    <span class="material-icons">account_circle</span>
                    <span class="ml-3">Usuarios</span>
                </a>

            </nav>
        </div>

         <!-- Main content -->
         <div class="flex-1 flex flex-col">
            <header class="bg-gray-800 shadow-lg h-16 sticky top-0 z-20 flex items-center justify-between px-6">
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

            <!-- Section contenido principal -->
            <section class="flex justify-center items-center max-w-4xl mx-auto px-8 py-20 w-full bg-gray-900">
                <div class="relative flex flex-col bg-clip-border rounded-xl bg-gray-800 text-white">
                    <div class="relative bg-clip-border mt-4 mx-4 rounded-xl overflow-hidden bg-gray-800 text-white flex gap-2 flex-col md:flex-row items-start justify-between">
                        <div class="w-full mb-2">
                            <p class="block antialiased font-sans text-sm font-light leading-normal text-inherit mt-1 font-normal text-gray-400">
                                Agrega o edita usuarios de la base de datos con sus roles respectivos
                            </p>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-4 p-4">
                        <!-- Aquí comienzan los usuarios -->
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="relative flex flex-col bg-clip-border rounded-xl bg-gray-700 text-white rounded-lg border border-gray-600 p-4">
                                <div class="mb-4 flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="border border-gray-500 p-2.5 rounded-lg">
                                            <!-- Icono de usuario -->
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="h-6 w-6 text-gray-300">
                                                <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="block antialiased font-sans text-sm font-light leading-normal text-blue-gray-200 mb-1 font-bold">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </p>
                                            <p class="block antialiased font-sans text-base font-light leading-relaxed text-gray-500 text-xs font-normal">
                                                Rol: <?php echo htmlspecialchars($row['rol']); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <button class="text-xs py-2 px-4 rounded-lg text-white hover:bg-gray-900/10 active:bg-gray-900/20 flex items-center gap-2">
                                            Editar usuario
                                        </button>
                                        <button class="text-xs py-2 px-4 rounded-lg text-red-500 hover:bg-red-500/10 active:bg-red-500/30 flex items-center gap-2">
                                            Eliminar usuario
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Fin de la lista de usuarios -->
                    </div>
                </div>
            </section>
        </div>
    </div>

</body>
</html>