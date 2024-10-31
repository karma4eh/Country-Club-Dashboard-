<?php
include_once 'db_connection.php';

function obtenerTasaDolarBCV() {
    global $conn;

    // Obtener la fecha y hora actual
    $hoy = date('Y-m-d');
    $fecha_hora_actual = date('Y-m-d H:i:s');
    $hora_actual = date('H:i');
    $dia_semana = date('N'); // 1 = Lunes, ..., 5 = Viernes, 6 = Sábado, 7 = Domingo

    // No realizar scraping si es sábado (6) o domingo (7)
    if ($dia_semana > 5) {
        // Consultar la última tasa registrada
        $query = "SELECT tasa FROM tasa_dolar ORDER BY fecha_actualizacion DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $ultima_tasa = $result->fetch_assoc();
        
        return $ultima_tasa['tasa'] ?? null;
    }

    // Consultar la última tasa registrada y verificar si ya se actualizó hoy
    $query = "SELECT tasa, fecha_actualizacion, scraped_today FROM tasa_dolar ORDER BY fecha_actualizacion DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $ultima_tasa = $result->fetch_assoc();

    $tasa = $ultima_tasa['tasa'] ?? null;
    $fecha_ultima_actualizacion = $ultima_tasa['fecha_actualizacion'] ?? null;
    $scraped_today = $ultima_tasa['scraped_today'] ?? 0;

    // Solo realizar scraping si es después de las 5 PM y no se ha hecho ya hoy
    if ($hora_actual < "17:00" || ($fecha_ultima_actualizacion >= $hoy && $scraped_today)) {
        return $tasa;
    }

    // Realizar scraping del sitio del BCV
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bcv.org.ve/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Tiempo límite de 15 segundos

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

        // Guardar la nueva tasa en la base de datos si ha cambiado o si no se ha registrado hoy
        if ($tasa === null || $tasa != $tasa_dolar || $fecha_ultima_actualizacion < $hoy) {
            $stmt = $conn->prepare("INSERT INTO tasa_dolar (tasa, fecha_actualizacion, scraped_today) VALUES (?, ?, ?)");
            $scraped = 1; // Indicar que se ha hecho scraping hoy
            $stmt->bind_param("dsi", $tasa_dolar, $fecha_hora_actual, $scraped);
            $stmt->execute();
            $stmt->close();
        }

        return $tasa_dolar;
    }
    return $tasa;
}
?>
