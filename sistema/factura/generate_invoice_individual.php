<?php
include(dirname(__DIR__) . '../../conexion.php');
global $conection;


require('fpdf.php');

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Consulta para obtener los detalles de la orden seleccionada
    $stmt = mysqli_query($conection, "SELECT * FROM ordenes WHERE id = $order_id");

    if (!$stmt) {
        $error = mysqli_error($conection);
        echo $error;
        exit;
    }

    $row = mysqli_fetch_assoc($stmt);

    $stmt2 = mysqli_query(
        $conection,
        "SELECT r.*, ordenes_recetas.quantity as quantity, ordenes_recetas.quantity * r.price as total
        FROM ordenes_recetas
        LEFT JOIN recipe as r ON r.id = ordenes_recetas.receta_id
        WHERE orden_id = $order_id"
    );
    $recipes = [];
    $totalPrice = 0; // Inicializar la variable para almacenar el total

    while ($row2 = mysqli_fetch_assoc($stmt2)) {
        $recipes[] = $row2;
        $totalPrice += $row2['total']; // Sumar al total el valor calculado
    }

    $row['recipes'] = $recipes;
    $row['totalPrice'] = $totalPrice;

    // Crear el documento XML para esta orden
    $xml = new DOMDocument("1.0", "UTF-8");
    $xml->formatOutput = true;

    $orderElement = $xml->createElement("order");
    $xml->appendChild($orderElement);

    $totalElement = $xml->createElement("total", $totalPrice);
    $orderElement->appendChild($totalElement);

    foreach ($row as $key => $value) {
        if ($key == 'recipes') {
            $recipesElement = $xml->createElement("recipes");
            foreach ($value as $recipe) {
                $recipeElement = $xml->createElement("recipe");
                foreach ($recipe as $recipeKey => $recipeValue) {
                    $recipeField = $xml->createElement($recipeKey, $recipeValue);
                    $recipeElement->appendChild($recipeField);
                }
                $recipesElement->appendChild($recipeElement);
            }
            $orderElement->appendChild($recipesElement);
        } else {
            $field = $xml->createElement($key, $value);
            $orderElement->appendChild($field);
        }
    }

    $xmlFilePath = "reporte_individual/ordenes/orden_" . $row['id'] . ".xml";
    $xml->save($xmlFilePath);

   // echo "XML file generated for order ID {$row['id']}.\n";



    // Crear una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Courier', '', 12);

    // Leer el archivo XML correspondiente
    $xml = simplexml_load_file($xmlFilePath);
    $pdf->Image('../img/favicon.png', 165, 12, 35, 35, 'PNG');

    // Encabezado
    $pdf->SetFont('Courier', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode("Orden de producción"), 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode("Cliente: " . $xml->customer_name), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Fecha de pedido: " . $xml->created_at), 0, 1);
    $pdf->Cell(155);
    $pdf->Cell(35, 7, utf8_decode("Orden Nro. " . $xml->id), 0, 0, 'R');


    $pdf->Ln(10);

    // Tabla de productos
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->SetFillColor(179, 0, 75); // Color de fondo azul claro
    $pdf->Cell(80, 10, utf8_decode("Producto"), 1, 0, 'C', 1);
    $pdf->Cell(40, 10, utf8_decode("Precio"), 1, 0, 'C', 1);
    $pdf->Cell(30, 10, utf8_decode("Cantidad"), 1, 0, 'C', 1);
    $pdf->Cell(40, 10, utf8_decode("Total"), 1, 1, 'C', 1);

    $pdf->SetFont('Courier', '', 12);
    foreach ($xml->recipes->recipe as $recipe) {
        $pdf->Cell(80, 10, utf8_decode($recipe->name), 1);
        $pdf->Cell(40, 10, utf8_decode("$" . $recipe->price), 1, 0, 'R');
        $pdf->Cell(30, 10, utf8_decode($recipe->quantity), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode("$" . $recipe->total), 1, 1, 'R');
    }
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(155);
    $pdf->Cell(0, 10, utf8_decode("Total: $" . $row['totalPrice']), 0, 1, 'C');

    // Pie de página
    $pdf->Ln(10);
    $pdf->SetFont('Courier', 'BI', 10);
    date_default_timezone_set("America/Guayaquil");
    $fechaHoraActual = date("d/m/Y H:i:s");
    $pdf->Cell(0, 10, utf8_decode("¡Fin de reporte!"), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode($fechaHoraActual), 0, 1, 'C');


    // Guardar o mostrar el PDF
    $pdfFilePath = "reporte_individual/ordenes/factura_" . $row['id'] . ".pdf";
    $pdf->Output($pdfFilePath, "I"); // "F" para guardar el PDF en el servidor
    $pdf->Output($pdfFilePath, "F"); 
    
}

?>

