<?php
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirige al login
    header("Location: ../src/index.php");
    exit();
}

?>
