<?php
include(dirname(__DIR__) . '../../conexion.php');
global $conection;

require "fpdf.php";

// Obtén las fechas de report_product.php
$first_date = $_GET['first_date'] ?? '';
$second_date = $_GET['second_date'] ?? '';

// Crear el documento XML unificado
$xml = new DOMDocument("1.0", "UTF-8");
$xml->formatOutput = true;

$productsElement = $xml->createElement("products");
$xml->appendChild($productsElement);

$where_condition = '';

if (!empty($first_date) && !empty($second_date)) {
    // Convierte las fechas a formato válido para la consulta
    $formatted_first_date = date('Y-m-d H:i:s', strtotime($first_date));
    $formatted_second_date = date('Y-m-d H:i:s', strtotime($second_date));
    $where_condition = "WHERE p.date_add BETWEEN '$formatted_first_date' AND '$formatted_second_date'";
}

$query = mysqli_query($conection, "SELECT p.*, AVG(l.new_price) as average_price, COUNT(r.id_product_rule) as tendencia FROM product_i p
    LEFT JOIN product_log_update l ON p.id_producto = l.producto_id
    LEFT JOIN rule_recipe r ON p.id_producto = r.id_product_rule
    $where_condition
    GROUP BY p.id_producto
    ORDER BY p.id_producto ASC");

// Iterar a través de los resultados de la consulta y agregar elementos XML para cada producto
while ($row = mysqli_fetch_assoc($query)) {
    $productElement = $xml->createElement("product");

    // Agregar elementos con atributos al elemento producto
    foreach ($row as $key => $value) {
        $valueElement = $xml->createElement($key, $value);
        $productElement->appendChild($valueElement);
    }

    // Agregar el elemento producto al elemento productos
    $productsElement->appendChild($productElement);
}

$xmlFilePath = "reporte_global/inventario/inventarioProductos.xml";
$xml->save($xmlFilePath);

// Crear una instancia de FPDF
$pdf = new FPDF('L');
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Inventario Producto', 0, 1, 'C');
$pdf->Cell(0, 10, 'Citaviso', 0, 1, 'C');



// Leer el archivo XML unificado
$xml = simplexml_load_file($xmlFilePath);
$imageWidth = 35;
$imageHeight = 35;
$pdf->Image('../img/favicon.png', $pdf->GetPageWidth() - $imageWidth - 12, 12, $imageWidth, $imageHeight, 'PNG');

$pdf->Ln(15);

// Obtener el ancho de cada celda y la posición de inicio de la tabla centrada
$cellWidth = 30;
$cellHeight = 10;
$tableXStart = ($pdf->GetPageWidth() - ($cellWidth * 7)) / 2; // Centrar la tabla horizontalmente

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(179, 0, 75); // Color de fondo para encabezados de tabla
$pdf->SetXY($tableXStart, $pdf->GetY()); // Posicionar la tabla centrada en X
$pdf->Cell($cellWidth, $cellHeight, 'Codigo', 1, 0, 'C', 1);
$pdf->Cell($cellWidth, $cellHeight, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell($cellWidth, $cellHeight, 'Precio', 1, 0, 'C', 1);
$pdf->Cell($cellWidth, $cellHeight, 'Precio promedio', 1, 0, 'C', 1);
$pdf->Cell($cellWidth, $cellHeight, 'Stock', 1, 0, 'C', 1);
$pdf->Cell($cellWidth, $cellHeight, 'Tendencia', 1, 0, 'C', 1);
$pdf->Cell($cellWidth, $cellHeight, 'Fecha Registro', 1, 1, 'C', 1);

// Contenido de la tabla
$pdf->SetFont('Arial', '', 8);
foreach ($xml->product as $product) {
    $pdf->SetX($tableXStart); // Posicionar la tabla centrada en X
    $pdf->Cell($cellWidth, $cellHeight, utf8_decode($product->id_producto), 1);
    $pdf->Cell($cellWidth, $cellHeight, utf8_decode($product->name), 1);
    $pdf->Cell($cellWidth, $cellHeight, '$' . $product->price, 1);
    $pdf->Cell($cellWidth, $cellHeight, '$' . $product->average_price, 1);
    $pdf->Cell($cellWidth, $cellHeight, utf8_decode($product->stock), 1);
    $pdf->Cell($cellWidth, $cellHeight, utf8_decode($product->tendencia), 1);
    $pdf->Cell($cellWidth, $cellHeight, utf8_decode($product->date_add), 1);
    $pdf->Ln();
}

// Pie de página
$pdf->Ln(15);
$pdf->SetFont('Arial', 'BI', 10);
date_default_timezone_set("America/Guayaquil");
$fechaHoraActual = date("d/m/Y H:i:s");
$pdf->Cell(0, 10, utf8_decode("¡Fin de reporte!"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode($fechaHoraActual), 0, 1, 'C');

// Descargar el PDF
$pdfFilePath = "reporte_global/inventario/inventarioInicial.pdf";
$pdf->Output($pdfFilePath, "I");
$pdf->Output($pdfFilePath, "F");

?>
