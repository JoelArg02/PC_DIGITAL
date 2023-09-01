<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_ver_reportes']) || $_SESSION['permisos']['permiso_ver_reportes'] != 1) {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link rel="stylesheet" type="text/css" href="css/style_tables.css">
    <link rel="icon" type="image/jpg" href="img/favicon.png" />

    <style>
        .IO {
            position: fixed;
            bottom: 40px;
            right: 10px;
            border: 2px solid #eb4d4b;
            background: linear-gradient(to right, rgb(243, 37, 51), rgb(179, 0, 75));
            padding: 5px 10px;
        }

        .BT_IO {
            color: white;
            text-decoration: none;
        }
    </style>

</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1>Inventario inicial</h1>

        <div class="IO">
            <a class="BT_IO" target="_blank" href="factura/reporte_InventarioInicial.php">Reporte General</a>
        </div>

        <table>
            <tr>
                <th>Código</th>
                <th>Nombre producto</th>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Fecha Registro</th>
            </tr>
            <?php
            $sql_registe = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM product_i");
            $result_register = mysqli_fetch_array($sql_registe);
            $total_registro = $result_register['total_registro'];

            $por_pagina = 10;

            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);

            $query = "SELECT p.id_producto, p.name, p.supplier, p.price, p.stock, p.date_add FROM product_i p ORDER BY p.id_producto ASC LIMIT $desde,$por_pagina";

            $result = mysqli_query($conection, $query);

            if (!$result) {
                die("Error en la consulta: " . mysqli_error($conection));
            }

            while ($data = mysqli_fetch_array($result)) {
            ?>
                <tr>
                    <td>
                        <?php echo $data['id_producto']; ?>
                    </td>
                    <td>
                        <?php echo $data['name']; ?>
                    </td>
                    <td>
                        <?php echo $data['supplier']; ?>
                    </td>
                    <td>
                        <?php echo $data['price']; ?>
                    </td>
                    <td>
                        <?php echo $data['stock']; ?>
                    </td>
                    <td>
                        <?php echo $data['date_add']; ?>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <div class="paginador">
            <ul>
                <?php
                if ($pagina != 1) {
                ?>
                    <li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i></a></li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fa-solid fa-backward"></i></a></li>
                <?php
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li class="pageSelected">' . $i . '</li>';
                    } else {
                        echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
                    }
                }

                if ($pagina != $total_paginas) {
                ?>
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fa-solid fa-forward"></i></a></li>
                    <li><a href="?pagina=<?php echo $total_paginas; ?> "><i class="fa-solid fa-forward-step"></i></a></li>
                <?php } ?>
            </ul>
        </div>

    </section>
    <div class="popup-container" id="popupContainer">
        <div class="popup-content" id="popupContent">
        </div>
        <span class="close-button" onclick="closePopup()">&times;</span>
    </div>

    <div class="overlay" id="overlay" onclick="closePopup()"></div>

</body>

</html>