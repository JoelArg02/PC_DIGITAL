<?php
include(dirname(__DIR__) . '../../conexion.php');
global $conection;

require "fpdf.php";

// Crear el documento XML unificado
$xml = new DOMDocument("1.0", "UTF-8");
$xml->formatOutput = true;

$productsElement = $xml->createElement("products");
$xml->appendChild($productsElement);

$query = "SELECT * FROM product_i";
$result = mysqli_query($conection, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $productElement = $xml->createElement("product");

    foreach ($row as $key => $value) {
        $field = $xml->createElement($key, $value);
        $productElement->appendChild($field);
    }

    $productsElement->appendChild($productElement);
}

$xmlFilePath = "reporte_global/inventario/inventarioInicial.xml";
$xml->save($xmlFilePath);

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Inventario Inicial', 0, 1, 'C');
$pdf->Cell(0, 10, 'Citaviso', 0, 1, 'C');



// Leer el archivo XML unificado
$xml = simplexml_load_file($xmlFilePath);
$pdf->Image('../img/favicon.png', 165, 12, 35, 35, 'PNG');
$pdf->Ln(15);

// Generar el contenido del PDF utilizando los datos del XML
$pdf->SetFont('Courier', 'B', 10);
$pdf->SetFillColor(179, 0, 75); // Color de fondo para encabezados de tabla
$pdf->Cell(30, 10, 'Codigo', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Proveedor', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Precio', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Stock', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Fecha Registro', 1, 1, 'C', 1);

$pdf->SetFont('Courier', '', 8);

// Agregar cada fila de datos en la tabla
foreach ($xml->product as $product) {
    $pdf->Cell(30, 10, utf8_decode($product->id_producto), 1);
    $pdf->Cell(40, 10, utf8_decode($product->name), 1);
    $pdf->Cell(40, 10, utf8_decode($product->supplier), 1);
    $pdf->Cell(30, 10, '$' . $product->price, 1);
    $pdf->Cell(20, 10, utf8_decode($product->stock), 1);
    $pdf->Cell(40, 10, utf8_decode($product->date_add), 1, 1);
}

$pdf->Ln(15);

$pdf->SetFont('Courier', 'BI', 10);
date_default_timezone_set("America/Guayaquil");
$fechaHoraActual = date("d/m/Y H:i:s");
$pdf->Cell(0, 10, utf8_decode("¡Fin de reporte!"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode($fechaHoraActual), 0, 1, 'C');

// Descargar el PDF
$pdfFilePath = "reporte_global/inventario/inventarioInicial.pdf";
$pdf->Output($pdfFilePath, "I");
$pdf->Output($pdfFilePath, "F");
?>
