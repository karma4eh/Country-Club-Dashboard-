<?php
include_once 'db_connection.php';

function obtenerTasaDolarBCV() {
    global $conn;

    // Obtener la fecha de hoy y la hora actual
    $hoy = date('Y-m-d');
    $hora_actual = date('H:i');
    $dia_semana = date('N'); // 1 = Lunes, ..., 5 = Viernes, 6 = Sábado, 7 = Domingo

    // Consultar la última tasa registrada en la base de datos
    $query = "SELECT tasa, scraped_today FROM tasa_dolar ORDER BY fecha_actualizacion DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $ultima_tasa = $result->fetch_assoc();
    
    $tasa = $ultima_tasa['tasa'] ?? null;
    $scraped_today = $ultima_tasa['scraped_today'] ?? 0;

    // No realizar scraping si es sábado o domingo
    if ($dia_semana > 5) {
        return $tasa;
    }

    // Solo realizar scraping si es después de las 5 PM y no se ha hecho ya hoy
    if ($hora_actual < "17:00" || $scraped_today) {
        return $tasa;
    }

    // Realizar scraping
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bcv.org.ve/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Tiempo límite de 10 segundos para la conexión

    $html = curl_exec($ch);
    $curl_error = curl_errno($ch);
    curl_close($ch);

    // Si cURL falla o el tiempo se agota, retorna la última tasa disponible
    if ($html === false || $curl_error) {
        return $tasa;
    }

    // Procesar el HTML y obtener la tasa
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $tasa_dolar_element = $xpath->query("(//div[contains(@class, 'centrado')]/strong)[last()]");

    if ($tasa_dolar_element->length > 0) {
        $tasa_dolar = trim($tasa_dolar_element[0]->textContent);
        $tasa_dolar = str_replace(',', '.', $tasa_dolar);
        $tasa_dolar = (float)$tasa_dolar;

        // Guardar la nueva tasa en la base de datos solo si ha cambiado
        if ($tasa === null || $tasa != $tasa_dolar) {
            $stmt = $conn->prepare("INSERT INTO tasa_dolar (tasa, fecha_actualizacion, scraped_today) VALUES (?, ?, ?)");
            $scraped = 1; // Indicar que se ha hecho scraping hoy
            $stmt->bind_param("dsi", $tasa_dolar, $hoy, $scraped);
            $stmt->execute();
            $stmt->close();
        }

        return $tasa_dolar;
    }
    return $tasa;
}
?>
