<?php

include "../fpdf186/fpdf.php";
include 'db_connection.php';

$pdf = new FPDF($orientation='P', $unit='mm');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);    
$textypos = 5;
$pdf->setY(12);
$pdf->setX(10);

// Datos de la empresa
$pdf->Cell(5, $textypos, "ASOCIACION CIVIL SAN CRISTOBAL COUNTRY CLUB");
$pdf->SetFont('Arial','B',10);    
$pdf->setY(30);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "DE:");
$pdf->SetFont('Arial','',10);    
$pdf->setY(35);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Asociación Civil San Cristobal Country Club");
$pdf->setY(40);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Calle Polígono del Tiro, Avenida Universidad");
$pdf->setY(45);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "San Cristóbal");

// Datos del cliente (socio)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $monto_dolares = $_POST['monto_dolares'];
    $descripcion = $_POST['descripcion'];

    // Obtener el ID del socio
    $query = "SELECT id, nombre, apellido, saldo FROM socios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $socio = $result->fetch_assoc();
        $socio_id = $socio['id'];
        $nombre_socio = $socio['nombre'];
        $apellido_socio = $socio['apellido'];
        $saldo_actual = $socio['saldo'];

        // Registrar el pago
        $query = "INSERT INTO pagos (socios_id, descripcion, monto) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isd', $socio_id, $descripcion, $monto_dolares);

        if ($stmt->execute()) {
            // Actualizar el último mes pagado y saldo
            $query_update = "UPDATE socios SET saldo = saldo + ?, ultimo_mes_pagado = NOW() WHERE id = ?";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bind_param('di', $monto_dolares, $socio_id);
            $stmt_update->execute();

            // Calcular nuevo saldo
            $nuevo_saldo = $saldo_actual + $monto_dolares;

            // Generar la factura
            $factura = [
                'nombre' => $nombre_socio,
                'apellido' => $apellido_socio,
                'cedula' => $cedula,
                'descripcion' => $descripcion,
                'monto_dolares' => number_format($monto_dolares, 2, '.', ''),
                'nuevo_saldo' => number_format($nuevo_saldo, 2, '.', ''),
                'fecha_pago' => date('Y-m-d H:i:s'),
            ];

            // Datos del cliente
            $pdf->SetFont('Arial', 'B', 10);    
            $pdf->setY(30);
            $pdf->setX(75);
            $pdf->Cell(5, $textypos, "PARA:");
            $pdf->SetFont('Arial', '', 10);    
            $pdf->setY(35);
            $pdf->setX(75);
            $pdf->Cell(5, $textypos, $nombre_socio . " " . $apellido_socio);
            $pdf->setY(40);
            $pdf->setX(75);
            $pdf->Cell(5, $textypos, "Cédula: " . $cedula);
            $pdf->setY(45);
            $pdf->setX(75);
            $pdf->Cell(5, $textypos, "Saldo Actual: $" . number_format($saldo_actual, 2, ".", ","));

            // Información de la factura
            $pdf->SetFont('Arial', 'B', 10);    
            $pdf->setY(30);
            $pdf->setX(135);
            $pdf->Cell(5, $textypos, "FACTURA #12345");
            $pdf->SetFont('Arial', '', 10);    
            $pdf->setY(35);
            $pdf->setX(135);
            $pdf->Cell(5, $textypos, "Fecha: " . date('d/M/Y'));
            $pdf->setY(40);
            $pdf->setX(135);
            $pdf->Cell(5, $textypos, "Vencimiento: " . date('d/M/Y', strtotime("+30 days")));

            // Tabla de productos
            $pdf->setY(60);
            $pdf->setX(135);
            $pdf->Ln();

            $header = array("Cod.", "Descripcion", "Cant.", "Precio", "Total");
            $products = array(
                array("0010", "Producto 1", 2, 120, 0),
                array("0024", "Producto 2", 5, 80, 0),
                array("0001", "Producto 3", 1, 40, 0),
            );

            // Column widths
            $w = array(20, 95, 20, 25, 25);
            for($i=0; $i<count($header); $i++)
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
            $pdf->Ln();

            $total = 0;
            foreach($products as $row) {
                $pdf->Cell($w[0], 6, $row[0], 1);
                $pdf->Cell($w[1], 6, $row[1], 1);
                $pdf->Cell($w[2], 6, number_format($row[2]), 1, 0, 'R');
                $pdf->Cell($w[3], 6, "$ ".number_format($row[3], 2, ".", ","), 1, 0, 'R');
                $pdf->Cell($w[4], 6, "$ ".number_format($row[3] * $row[2], 2, ".", ","), 1, 0, 'R');
                $pdf->Ln();
                $total += $row[3] * $row[2];
            }

            // Subtotales y Totales
            $yposdinamic = 60 + (count($products) * 10);
            $pdf->setY($yposdinamic);
            $pdf->setX(235);
            $pdf->Ln();

            $data2 = array(
                array("Subtotal", $total),
                array("Descuento", 0),
                array("Impuesto", 0),
                array("Total", $total),
            );
            $w2 = array(40, 40);
            foreach($data2 as $row) {
                $pdf->setX(115);
                $pdf->Cell($w2[0], 6, $row[0], 1);
                $pdf->Cell($w2[1], 6, "$ ".number_format($row[1], 2, ".", ","), '1', 0, 'R');
                $pdf->Ln();
            }

            // Términos y condiciones
            $yposdinamic += (count($data2) * 10);
            $pdf->SetFont('Arial', 'B', 10);    
            $pdf->setY($yposdinamic);
            $pdf->setX(10);
            $pdf->Cell(5, $textypos, "TERMINOS Y CONDICIONES");
            $pdf->SetFont('Arial', '', 10);    

            $pdf->setY($yposdinamic + 10);
            $pdf->setX(10);
            $pdf->Cell(5, $textypos, "El cliente se compromete a pagar la factura.");
            $pdf->setY($yposdinamic + 20);
            $pdf->setX(10);
            $pdf->Cell(5, $textypos, "Powered by Evilnapsis");

            $pdf->output();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar el pago']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Socio no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no válido']);
}
?>
