<?php
session_start();
$error_message = "";
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']); // Captura el mensaje de error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Club</title>
    <link href="./output.css" rel="stylesheet">
    <style>
        .input-error {
            border-color: red; /* Cambia el borde a rojo si hay un error */
        }
        .error-message {
            color: red; /* Color rojo para el mensaje de error */
            font-size: 0.875rem; /* Tamaño de fuente más pequeño para el mensaje */
        }
    </style>
</head>
<body>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
                Country Club    
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Inicia sesión en tu cuenta
                    </h1>

                    <!-- Mensaje de error -->
                    <div id="error-message" class="error-message">
                        <?php if ($error_message): ?>
                            <?php echo $error_message; ?>
                        <?php endif; ?>
                    </div>

                    <form id="login-form" class="space-y-4 md:space-y-6" action="../backend/login.php" method="POST">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu Usuario</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white <?php if ($error_message) echo 'input-error'; ?>" 
                                    placeholder="Usuario" required="">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-user"></i></span>
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" 
                                    placeholder="••••••••" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white <?php if ($error_message) echo 'input-error'; ?>" 
                                    required="">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-lock"></i></span>
                            </div>
                        </div>

                        <!-- Botón con color diferente -->
                        <button type="submit" 
                            class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                            Inicia Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
