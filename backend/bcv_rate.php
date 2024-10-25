<?php
include_once 'db_connection.php';

function obtenerTasaDolarBCV() {
    global $conn;

    // Verificar si ya se ha actualizado la tasa hoy
    $hoy = date('Y-m-d');
    $query = "SELECT * FROM tasa_dolar WHERE fecha_actualizacion = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $hoy);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si ya hay una tasa para hoy, devolver esa tasa y no hacer scraping
    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        // echo "La tasa de hoy ya fue actualizada: " . number_format($fila['tasa'], 4, '.', ''); // Comentado
        return $fila['tasa']; // Devolver la tasa de hoy
    }

    // Realizar scraping de la página del BCV si no se ha actualizado hoy
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bcv.org.ve/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $html = curl_exec($ch);
    curl_close($ch);

    if ($html === false) {
        return null;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    // Seleccionar el último elemento <strong> con la tasa
    $tasa_dolar_element = $xpath->query("(//div[contains(@class, 'centrado')]/strong)[last()]");

    if ($tasa_dolar_element->length > 0) {
        // Limpiar y convertir el valor a float
        $tasa_dolar = trim($tasa_dolar_element[0]->textContent);
        $tasa_dolar = str_replace(',', '.', $tasa_dolar);  
        $tasa_dolar = (float)$tasa_dolar;

        // Insertar la nueva tasa en la base de datos con la fecha de hoy
        $stmt = $conn->prepare("INSERT INTO tasa_dolar (tasa, fecha_actualizacion) VALUES (?, ?)");
        $stmt->bind_param("ds", $tasa_dolar, $hoy);
        $stmt->execute();
        $stmt->close();

        // echo "Nueva tasa insertada: " . number_format($tasa_dolar, 4, '.', ''); // Comentado
        return $tasa_dolar;
    } else {
        // echo "No se pudo obtener la tasa del dólar."; // Comentado
        return null;
    }
}

// Llamar a la función (opcionalmente puedes remover esta línea si no la necesitas en este archivo)
obtenerTasaDolarBCV();
?>
