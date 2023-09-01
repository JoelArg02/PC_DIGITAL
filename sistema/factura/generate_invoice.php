<?php
include(dirname(__DIR__) . '../../conexion.php');
global $conection;

require "fpdf.php";

$stmt = mysqli_query($conection, "SELECT * FROM ordenes");

if (!$stmt) {
    $error = mysqli_error($conection);
    echo $error;
    exit;
}

// Crear el documento XML unificado
$xml = new DOMDocument("1.0", "UTF-8");
$xml->formatOutput = true;

$ordersElement = $xml->createElement("orders");
$xml->appendChild($ordersElement);

while ($row = mysqli_fetch_assoc($stmt)) {
    $stmt2 = mysqli_query(
        $conection,
        "SELECT r.*, ordenes_recetas.quantity as quantity, ordenes_recetas.quantity * r.price as total
        FROM ordenes_recetas
        LEFT JOIN recipe as r ON r.id = ordenes_recetas.receta_id
        WHERE orden_id = {$row['id']}"
    );

    $orderElement = $xml->createElement("order");
    
    $totalPrice = 0;
    $recipesElement = $xml->createElement("recipes");

    while ($row2 = mysqli_fetch_assoc($stmt2)) {
        $recipeElement = $xml->createElement("recipe");
        foreach ($row2 as $recipeKey => $recipeValue) {
            $recipeField = $xml->createElement($recipeKey, $recipeValue);
            $recipeElement->appendChild($recipeField);
        }
        $recipesElement->appendChild($recipeElement);
        $totalPrice += $row2['total'];
    }

    $orderElement->appendChild($recipesElement);
    $totalElement = $xml->createElement("total", $totalPrice);
    $orderElement->appendChild($totalElement);

    foreach ($row as $key => $value) {
        $field = $xml->createElement($key, $value);
        $orderElement->appendChild($field);
    }

    $ordersElement->appendChild($orderElement);
}

$xmlFilePath = "reporte_global/ordenes/factura_general.xml";
$xml->save($xmlFilePath);

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 16);
$pdf->Cell(40, 10, 'Factura General');

// Leer el archivo XML unificado
$xml = simplexml_load_file($xmlFilePath);

// Generar el contenido del PDF utilizando los datos del XML
foreach ($xml->order as $order) {
    $pdf->SetFont('Courier', 'B', 14);
    $pdf->Ln(10);

    $pdf->Image('../img/favicon.png', 165, 12, 35, 35, 'PNG');
    $pdf->Cell(35, 7, utf8_decode("Orden Nro. " . $order->id), 0, 0, 'R');
    $pdf->Ln(10);
    
    $pdf->SetFont('Courier', '', 12);
    $pdf->Cell(0, 10, 'Cliente: ' . $order->customer_name, 0, 1);
    $pdf->Cell(0, 10, 'Fecha de pedido: ' . $order->created_at, 0, 1);
    $pdf->Cell(0, 10, 'Total: $' . $order->total, 0, 1);

    $pdf->SetFont('Courier', 'B', 12);
    $pdf->SetFillColor(179, 0, 75); // Color de fondo para encabezados de tabla
    $pdf->Cell(60, 10, 'Producto', 1, 0, 'C', 1);
    $pdf->Cell(40, 10, 'Precio', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->Cell(40, 10, 'Total', 1, 1, 'C', 1);
    $pdf->SetFont('Courier', '', 12);

    foreach ($order->recipes->recipe as $recipe) {
        $pdf->SetFont('Courier', '', 12);
        $pdf->Cell(60, 10, $recipe->name, 1);
        $pdf->Cell(40, 10, '$' . $recipe->price, 1);
        $pdf->Cell(30, 10, $recipe->quantity, 1);
        $pdf->Cell(40, 10, '$' . $recipe->total, 1);
        $pdf->Ln();
    }

    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(130, 10, 'Total:', 1);
    $pdf->Cell(40, 10, '$' . $order->total, 1);
    $pdf->Ln(15);
}

$pdf->Ln(15);
$pdf->SetFont('Courier', 'BI', 10);
date_default_timezone_set("America/Guayaquil");
$fechaHoraActual = date("d/m/Y H:i:s");
$pdf->Cell(0, 10, utf8_decode("¡Fin de reporte!"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode($fechaHoraActual), 0, 1, 'C');

// Descargar el PDF
$pdfFilePath = "reporte_global/ordenes/factura_general.pdf";
$pdf->Output($pdfFilePath, "I");
$pdf->Output($pdfFilePath, "F");
?>
