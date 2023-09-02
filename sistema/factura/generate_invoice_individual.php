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
        "SELECT 
        r.id, r.user_id,
        (SELECT descripcion FROM producto WHERE producto.codproducto = rr.id_product_rule) as descripcion_producto,
        p.precio, r.estatus, r.created_at, r.updated_at,
        ord.quantity as quantity,
        ord.quantity * r.price as total
    FROM 
        ordenes_recetas as ord,
        rule_recipe as rr,
        recipe as r,
        producto as p
    WHERE 
        r.id = ord.receta_id
        AND rr.id_recipe = r.id
        AND ord.orden_id =  $order_id
        AND ord.receta_id = rr.id_recipe
        AND ord.receta_id = r.id
        AND rr.id_product_rule = p.codproducto;"
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
    $pdf->Cell(0, 10, utf8_decode("Proforma"), 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode("Cliente: " . $xml->customer_name), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Fecha de elaboracion: " . $xml->created_at), 0, 1);
    $pdf->Ln(); // Agrega un salto de línea

    $pdf->Cell(155);
    $pdf->Cell(35, 7, utf8_decode("Proforma Nro. " . $xml->id), 0, 0, 'R');


    $pdf->Ln(10);

    // Tabla de productos
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->SetFillColor(179, 0, 75); // Color de fondo azul claro
    $pdf->Cell(105, 10, utf8_decode("Producto"), 1, 0, 'C', 1);
    $pdf->Cell(30, 10, utf8_decode("Precio"), 1, 0, 'C', 1);
    $pdf->Cell(25, 10, utf8_decode("Cantidad"), 1, 0, 'C', 1);
    $pdf->Cell(30, 10, utf8_decode("Total"), 1, 1, 'C', 1);

    $pdf->SetFont('Courier', '', 12);
    foreach ($xml->recipes->recipe as $recipe) {
        // Ajusta el ancho de la celda para la descripción del producto
        $pdf->Cell(105, 10, utf8_decode(substr($recipe->descripcion_producto, 0, 30)), 1);
        $pdf->Cell(30, 10, utf8_decode("$" . $recipe->precio), 1, 0, 'R');
        $pdf->Cell(25, 10, utf8_decode($recipe->quantity), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode("$" . $recipe->total), 1, 1, 'R');
    }
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(155);
    $pdf->Cell(0, 10, utf8_decode("Total: $" . $row['totalPrice']), 0, 1, 'C');

    // Pie de página
    $pdf->Ln(10);
    $pdf->SetFont('Courier', 'BI', 10);
    date_default_timezone_set("America/Guayaquil");
    $fechaHoraActual = date("d/m/Y H:i:s");
    $pdf->Cell(0, 10, utf8_decode("¡Esperamos verte pronto!"), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode($fechaHoraActual), 0, 1, 'C');


    // Guardar o mostrar el PDF
    $pdfFilePath = "reporte_individual/ordenes/factura_" . $row['id'] . ".pdf";
    $pdf->Output($pdfFilePath, "I"); // "F" para guardar el PDF en el servidor
    $pdf->Output($pdfFilePath, "F");

}

?>