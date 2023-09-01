<?php
session_start();

global $conection;

if (!isset($_SESSION['permisos']['permiso_ver_ordenes']) || $_SESSION['permisos']['permiso_ver_ordenes'] != 1) {
    header("location: index.php");
    exit();
}
include "../conexion.php";

$orders = [];

$stmt = mysqli_query($conection, "SELECT * FROM ordenes where estatus = 1");

if (!$stmt) {
    $error = mysqli_error($conection);
    echo $error;
    exit;
}

while ($row = mysqli_fetch_assoc($stmt)) {
    $stmt2 = mysqli_query(
        $conection,
        "SELECT r.*, ordenes_recetas.quantity as quantity, ordenes_recetas.quantity * r.price as total
        FROM ordenes_recetas
        LEFT JOIN recipe as r ON r.id = ordenes_recetas.receta_id
        WHERE orden_id = {$row['id']}"
    );
    $recipes = [];

    while ($row2 = mysqli_fetch_assoc($stmt2)) {
        $recipes[] = $row2;
    }

    $row['recipes'] = $recipes;
    $orders[] = $row;
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de ordenes</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/jpg" href="img/favicon.png" />

    <style>
        .order-container {
            max-width: 900px;
            margin: 0 auto 20px;
        }

        .ui-table.recipes-added {
            background: #f1f1f1;
            border-radius: 5px;
        }

        .recipes-added a.delete {
            color: #eb4d4b;
        }

        .order-container .recipes>strong {
            margin-bottom: 15px;
            display: block;
        }

        .RT {
            position: fixed;
            top: 60px;
            right: 10px;
            border: 2px solid #eb4d4b;
            background: linear-gradient(to right, rgb(243, 37, 51), rgb(179, 0, 75));
            padding: 5px 10px;
        }

        .BT_RT {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="alert">
        <?php
        echo isset($alert) ? $alert : '';
        echo isset($_SESSION['popup_message']) ? '<p class="msg_info" id="popupMessage">' . $_SESSION['popup_message'] . '</p>' : '';
        unset($_SESSION['popup_message']);
        ?>
    </div>
    <?php include(__DIR__ . '/includes/header.php'); ?>
    <main id="container" class="ui-container">
        <div class="RT">
            <a class="BT_RT" target="_blank"
                href="factura/generate_invoice.php?order_id=<?php echo $order['id']; ?>">Reporte General</a>
        </div>
        <?php foreach ($orders as $order): ?>
            <div class="ui-box order-container">
                <h3 class="ui-box-title">Orden #
                    <?php echo str_pad($order['id'], 7, "0", STR_PAD_LEFT) ?>
                </h3>
                <div class="ui-box-content">
                    <div><strong>Cliente:</strong>
                        <?php echo $order['customer_name'] ?>
                    </div>
                    <div><strong>Fecha:</strong>
                        <?php echo $order['created_at'] ?>
                    </div>
                    <div class="recipes">
                        <strong>Hojas Tecnicas:</strong>
                        <div class="ui-table recipes-added minimal small">
                            <div class="row header">
                                <div class="column">Hoja Tecnica</div>
                                <div class="column">Precio Unitario</div>
                                <div class="column">Cantidad</div>
                                <div class="column min-width">Total</div>
                            </div>
                            <?php $subtotal = 0; ?>
                            <?php foreach ($order['recipes'] as $recipe): ?>
                                <div class="row">
                                    <div class="column name">
                                        <?php echo $recipe['name']; ?>
                                    </div>
                                    <div id="suggestion" style="display: none;">Dale clic para ver los materiales a usar</div>

                                    <div class="column price">$
                                        <?php echo $recipe['price']; ?>
                                    </div>
                                    <div class="column quantity">
                                        <?php echo $recipe['quantity']; ?>
                                    </div>
                                    <div class="column total">
                                        <strong>$
                                            <?php echo $recipe['total']; ?>
                                        </strong>
                                    </div>
                                </div>
                                <?php
                                $subtotal += $recipe['total'];
                                ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="ui-box-footer">
                    <strong>Total: </strong>
                    <span>$
                        <?php echo $subtotal; ?>
                    </span>
                    <a target="_blank" href="factura/generate_invoice_individual.php?order_id=<?php echo $order['id']; ?>"
                        class="ui-button ui-button green">Reporte</a>
                    <a href="process/process_delete_order.php?order_id=<?php echo $order['id']; ?>"
                        class="ui-button ui-button green">Reversar Orden</a>

                </div>
            </div>

        <?php endforeach; ?>

    </main>
</body>

</html>