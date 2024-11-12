<?php
include '../backend/db_connection.php';
session_start();

// Verifica si el usuario ha iniciado sesión
include_once '../backend/verificar_seccion.php';

// Conexión a la base de datos
// Obtén el ID del usuario logeado desde la sesión
$logged_in_user_id = $_SESSION['usuario_id']; // Asegúrate de que 'user_id' esté en la sesión

// Consulta para obtener los usuarios
$query = "SELECT id, nombre_completo, email, rol FROM usuarios";
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
    <title>Country Club -Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100">
<!-- Modal para Registrar Usuario -->
<div id="registerModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-gray-800 text-white rounded-lg shadow-lg w-1/3 p-6">
        <h2 class="text-xl font-semibold mb-4">Registrar Usuario</h2>
        <form id="registerUserForm" action="../backend/register_user.php" method="POST">
            <label class="block text-sm mb-2">Nombre Completo:</label>
            <input type="text" name="nombre_completo" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>

            <label class="block text-sm mb-2">Email:</label>
            <input type="email" name="email" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>

            <label class="block text-sm mb-2">Rol:</label>
            <select name="rol" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>
                <option value="admin">Admin</option>
                <option value="secretaria">Secretaria</option>
                <option value="vigilante">Vigilante</option>
            </select>

            <label class="block text-sm mb-2">Contraseña:</label>
            <input type="password" name="password" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>

            <label class="block text-sm mb-2">Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeRegisterModal()" class="px-4 py-2 bg-gray-600 rounded">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 rounded">Registrar</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal para Editar Usuario -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-gray-800 text-white rounded-lg shadow-lg w-1/3 p-6">
        <h2 class="text-xl font-semibold mb-4">Editar Usuario</h2>
        <form id="editUserForm" action="../backend/edit_users.php" method="POST">
            <input type="hidden" name="id" id="editUserId">
            <label class="block text-sm mb-2">Nombre Completo:</label>
            <input type="text" name="nombre_completo" id="editNombreCompleto" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>
            
            <label class="block text-sm mb-2">Email:</label>
            <input type="email" name="email" id="editEmail" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>

            <label class="block text-sm mb-2">Rol:</label>
                <select name="rol" id="editRol" class="w-full p-2 mb-4 rounded bg-gray-700 text-white" required>
                <option value="admin">Admin</option>
                <option value="secretaria">Secretaria</option>
                <option value="vigilante">Vigilante</option>
                </select>


            <label class="block text-sm mb-2">Nueva Contraseña (opcional):</label>
            <input type="password" name="new_password" id="editPassword" class="w-full p-2 mb-4 rounded bg-gray-700 text-white">

            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-600 rounded">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 rounded">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Confirmación de Eliminación -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-gray-800 text-white rounded-lg shadow-lg w-1/3 p-6">
        <h2 class="text-xl font-semibold mb-4">Confirmar Eliminación</h2>
        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
        <div class="flex justify-end gap-4 mt-6">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-600 rounded">Cancelar</button>
            <a id="confirmDeleteButton" href="#" class="px-4 py-2 bg-red-600 rounded">Eliminar</a>
        </div>
    </div>
</div>


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
   <section class="flex justify-center items-start max-w-4xl mx-auto px-8 py-8 w-full bg-gray-900 overflow-y-auto">
                <div class="relative flex flex-col bg-clip-border rounded-xl bg-gray-800 text-white w-full">
                    <div class="relative bg-clip-border mt-4 mx-4 rounded-xl overflow-hidden bg-gray-800 text-white flex flex-col gap-2">
                        <div class="w-full mb-2">
                            <p class="block antialiased font-sans text-sm font-light leading-normal text-inherit mt-1 font-normal text-gray-400">
                                Agrega o edita usuarios de la base de datos con sus roles respectivos
                            </p>
                        </div>
                        
                    </div>
                    <div class="p-6 flex flex-col gap-4">
                        <!-- Aquí comienzan los usuarios -->
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="relative flex flex-col bg-clip-border rounded-xl bg-gray-700 text-white border border-gray-600 p-4">
                                <div class="mb-4 flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="border border-gray-500 p-2.5 rounded-lg">
                                            <!-- Icono de usuario -->
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="h-6 w-6 text-gray-300">
                                                <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="block antialiased font-sans text-sm font-light leading-normal text-blue-gray-200 mb-1 font-bold">
                                                <?php echo htmlspecialchars($row['nombre_completo']); ?>
                                            </p>
                                            <p class="block antialiased font-sans text-sm font-light leading-normal text-blue-gray-200 mb-1 font-bold">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </p>
                                            <p class="block antialiased font-sans text-base font-light leading-relaxed text-gray-500 text-xs font-normal">
                                                Rol: <?php echo htmlspecialchars($row['rol']); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                       <!-- Editar usuario -->
                                <a href="javascript:void(0);" onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo $row['nombre_completo']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['rol']; ?>')" class="text-xs py-2 px-4 rounded-lg text-white hover:bg-gray-900/10 active:bg-gray-900/20">
                                Editar usuario
                                </a>

                            <!-- Eliminar usuario -->
                            <?php if ($row['id'] != $logged_in_user_id) { ?>
                              <a href="javascript:void(0);" onclick="openDeleteModal('<?php echo $row['id']; ?>')" class="text-xs py-2 px-4 rounded-lg text-red-500 hover:bg-red-500/10 active:bg-red-500/30">
                                 Eliminar usuario
                               </a>
                            <?php } else { ?>
                             <span class="text-xs py-2 px-4 rounded-lg text-gray-400">No se puede eliminar</span>
                                <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Fin de la lista de usuarios -->
                    </div>
                    <button onclick="openRegisterModal()" class="px-4 py-2 bg-green-600 rounded m-6">Registrar Usuario</button>
                </div>
            </section>
        </div>
    </div>
</body>
<script>
// Funciones para abrir y cerrar el modal de edición
function openEditModal(id, nombre, email, rol) {
    document.getElementById("editUserId").value = id;
    document.getElementById("editNombreCompleto").value = nombre;
    document.getElementById("editEmail").value = email;
    document.getElementById("editRol").value = rol;
    document.getElementById("editModal").classList.remove("hidden");
}

function closeEditModal() {
    document.getElementById("editModal").classList.add("hidden");
}

// Funciones para abrir y cerrar el modal de eliminación
function openDeleteModal(id) {
    const deleteUrl = `../backend/delete_users.php?id=${id}`;
    document.getElementById("confirmDeleteButton").href = deleteUrl;
    document.getElementById("deleteModal").classList.remove("hidden");
}

function closeDeleteModal() {
    document.getElementById("deleteModal").classList.add("hidden");
}
// Funciones para abrir y cerrar el modal de registro
function openRegisterModal() {
    document.getElementById("registerModal").classList.remove("hidden");
}

function closeRegisterModal() {
    document.getElementById("registerModal").classList.add("hidden");
}
</script>
</html>