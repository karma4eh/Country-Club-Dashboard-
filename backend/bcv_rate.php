<?php
include_once 'db_connection.php';  

function obtenerTasaDolarBCV() {
    global $conn;  

    
    $result = $conn->query("SELECT tasa, fecha_actualizacion FROM tasa_dolar ORDER BY fecha_actualizacion DESC LIMIT 1");
    $row = $result->fetch_assoc();

    // Si la tasa ya se actualizó hoy
    $fechaHoy = date('Y-m-d');
    if ($row && date('Y-m-d', strtotime($row['fecha_actualizacion'])) == $fechaHoy) {
        return (float)$row['tasa'];
    }

    // Si no, realizar scraping 
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
    
    // Seleccionar el último elemento <strong> 
    $tasa_dolar_element = $xpath->query("(//div[contains(@class, 'centrado')]/strong)[last()]");

    if ($tasa_dolar_element->length > 0) {
        $tasa_dolar = trim($tasa_dolar_element[0]->textContent);
        $tasa_dolar = str_replace(',', '.', $tasa_dolar);  
        $tasa_dolar = (float)$tasa_dolar;

        // Insertar la nueva tasa 
        $stmt = $conn->prepare("INSERT INTO tasa_dolar (tasa) VALUES (?)");
        $stmt->bind_param("d", $tasa_dolar);
        $stmt->execute();
        $stmt->close();

        return $tasa_dolar;
    } else {
        return null;
    }
}

// Función para calcular bolívares
function calcularBolivares($dolares) {
    $tasa = obtenerTasaDolarBCV();
    if ($tasa !== null) {
        $bolivares = $dolares * $tasa;
        return round($bolivares, 2);
    } else {
        return "Error obteniendo la tasa del BCV.";
    }
}

?>
