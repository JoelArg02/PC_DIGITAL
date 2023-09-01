<?php
include('../../conexion.php');
global $conection;

$error = null;

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Retrieve recipes associated with the order
    $stmt = mysqli_prepare($conection, "SELECT receta_id, quantity FROM ordenes_recetas WHERE orden_id = ?");

    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $stmt->close();

    // Update quantities of products based on recipes
    foreach ($recipes as $recipe) {
        $recipe_id = intval($recipe['receta_id']);
        $total_price = floatval($recipe['quantity']); // Assuming total price is stored as float

        $stmt = mysqli_prepare($conection, "SELECT id_product_rule, cantidad FROM rule_recipe WHERE id_recipe = ?");
        $stmt->bind_param('i', $recipe_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $products_used = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $stmt->close();

        foreach ($products_used as $product) {
            $product_id = intval($product['id_product_rule']);
            $quantity_used = doubleval($product['cantidad']);

            // Update product quantities
            $stmt = mysqli_prepare($conection, "UPDATE producto SET existencia = existencia + ? WHERE codproducto = ?");
            $stmt->bind_param('di', $quantity_used, $product_id);
            if (!$stmt->execute()) {
                $error = mysqli_error($conection);
                echo $error;
                exit;
            }
            $stmt->close();
        }
    }

    // Update order status to 0
    $stmt = mysqli_prepare($conection, "UPDATE ordenes SET estatus = 0 WHERE id = ?");
    $stmt->bind_param('i', $order_id);
    if (!$stmt->execute()) {
        $error = mysqli_error($conection);
        echo $error;
        exit;
    }
    $stmt->close();
    $_SESSION['popup_message'] = 'Orden reversada.';
    header("location: ../lista_ordenes.php");
    exit;
} else {
    $_SESSION['popup_message'] = 'Error';
    header("location: ../lista_ordenes.php");

}
?>