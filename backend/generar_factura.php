<?php
require('../fpd186/fpdf.php');
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $cedula = $_POST['cedula'];
    $montoDolares = $_POST['monto_dolares'];
    $descripcion = $_POST['descripcion'];
    $tasaBCV = obtenerTasaDolarBCV(); // Asegúrate de tener definida esta función

    // Calcular el monto en bolívares
    $montoBolivares = $montoDolares * $tasaBCV;

    // Generar el PDF de la factura
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Factura de Pago - Country Club', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Cédula: ' . $cedula, 0, 1);
    $pdf->Cell(0, 10, 'Monto en Dólares: $' . number_format($montoDolares, 2), 0, 1);
    $pdf->Cell(0, 10, 'Monto en Bolívares: Bs ' . number_format($montoBolivares, 2), 0, 1);
    $pdf->Cell(0, 10, 'Descripción: ' . $descripcion, 0, 1);
    $pdf->Cell(0, 10, 'Tasa BCV: ' . number_format($tasaBCV, 4), 0, 1);

    // Guardar el PDF en un directorio y obtener la ruta
    $pdfFilePath = '../invoices/factura_' . $cedula . '_' . time() . '.pdf';
    $pdf->Output('F', $pdfFilePath);

    // Leer el contenido del PDF para almacenarlo en la base de datos
    $pdfContent = file_get_contents($pdfFilePath);

    // Guardar el PDF en la base de datos
    $stmt = $conn->prepare("INSERT INTO facturas (cedula, monto_dolares, monto_bolivares, descripcion, tasa_bcv, pdf_factura) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddsss", $cedula, $montoDolares, $montoBolivares, $descripcion, $tasaBCV, $pdfContent);

    if ($stmt->execute()) {
        echo "Factura generada y guardada exitosamente.";
    } else {
        echo "Error al guardar la factura: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
