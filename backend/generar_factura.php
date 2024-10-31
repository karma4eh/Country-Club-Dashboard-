<?php
require('path/to/fpdf.php');

// Obtener la tasa del BCV
$bcv_data = file_get_contents('bcv_rate.php');
$bcv_data = json_decode($bcv_data, true);
$tasa_bcv = $bcv_data['tasa'] ?? 0; // Si no se obtiene la tasa, se establece en 0

$fecha = date('d/m/Y H:i:s'); // Formato de fecha: día/mes/año hora

class PDF extends FPDF {
    // Cabecera de página
    function Header() {
        // Logo del Country Club
        $this->Image('img/logo.png', 10, 8, 33); // Ajusta la posición y el tamaño según sea necesario
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Factura', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Datos de la factura
$nombre = $_GET['nombre'] ?? 'Nombre no disponible';
$apellido = $_GET['apellido'] ?? 'Apellido no disponible';
$monto = $_GET['monto_dolares'] ?? '0.00';
$descripcion = $_GET['descripcion'] ?? 'Sin descripción';

// Crear un nuevo PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Añadir contenido
$pdf->Cell(0, 10, 'Nombre: ' . $nombre . ' ' . $apellido, 0, 1);
$pdf->Cell(0, 10, 'Monto: $' . number_format((float)$monto, 2, '.', ''), 0, 1);
$pdf->Cell(0, 10, 'Descripción: ' . $descripcion, 0, 1);
$pdf->Cell(0, 10, 'Tasa del BCV: ' . $tasa_bcv, 0, 1);
$pdf->Cell(0, 10, 'Fecha: ' . $fecha, 0, 1);

// Guardar el PDF como "factura.pdf"
$pdfFilePath = 'factura_' . uniqid() . '.pdf'; // Generar un nombre único para el archivo
$pdf->Output('F', $pdfFilePath); // Guardar el archivo en el servidor

// Enviar el PDF al navegador para descarga
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($pdfFilePath) . '"');
readfile($pdfFilePath);

// Borrar el archivo después de enviarlo (opcional)
unlink($pdfFilePath); // Eliminar el archivo del servidor si no lo necesitas guardar
exit; // Asegúrate de salir después de enviar el archivo
?>
