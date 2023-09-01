<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_ver_productos']) || $_SESSION['permisos']['permiso_ver_productos'] != 1) {
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
    <link rel="icon" type="image/jpg" href="img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="css/style_tables.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="alert">
            <?php
            echo isset($alert) ? $alert : '';
            echo isset($_SESSION['popup_message']) ? '<p class="msg_info" id="popupMessage">' . $_SESSION['popup_message'] . '</p>' : '';
            unset($_SESSION['popup_message']);
            ?>
        </div>

        <script>
            setTimeout(function () {
                var popupMessage = document.getElementById("popupMessage");
                if (popupMessage) {
                    popupMessage.style.display = "none";
                }
            }, 4000);
        </script>

        <h1>Lista de Productos</h1>

        <?php if (isset($_SESSION['permisos']['permiso_crear_productos']) && $_SESSION['permisos']['permiso_crear_productos'] == 1) { ?>

            <a href="registro_producto.php" class="btn_new"
                onclick="loadPopupContentFromLink(this.href); return false;">Nuevo Producto</a>
            <a href="ingreso_producto.php" class="btn_new"
                onclick="loadPopupContentFromLink(this.href); return false;">Ingreso
                Producto</a>

        <?php } ?>

        <form action="buscar_producto.php" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" class="btn_search">
        </form>

        <table>
            <tr>
                <th>Código</th>
                <th>Nombre producto</th>
                <th>Medida</th>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>Stock</th>
                <?php if (isset($_SESSION['permisos']['permiso_crear_productos']) && $_SESSION['permisos']['permiso_crear_productos'] == 1) { ?>

                    <th>Acciones</th>
                <?php } ?>
            </tr>
            <?php
            $sql_registe = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM producto WHERE estatus = 1 ");
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

            $query = "SELECT p.codproducto, p.proveedor, p.descripcion, p.medida, p.precio, p.existencia, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.id_supplier WHERE p.estatus = 1 ORDER BY p.codproducto ASC LIMIT $desde,$por_pagina";

            $result = mysqli_query($conection, $query);

            if (!$result) {
                die("Error en la consulta: " . mysqli_error($conection));
            }

            while ($data = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td>
                        <?php echo $data['codproducto']; ?>
                    </td>
                    <td>
                        <?php echo $data['descripcion']; ?>
                    </td>
                    <td>
                        <?php echo $data['medida']; ?>
                    </td>
                    <td>
                        <?php echo $data['proveedor']; ?>
                    </td>
                    <td>
                        <?php echo $data['precio']; ?>
                    </td>
                    <td>
                        <?php echo $data['existencia'] . " Unidades"; ?>
                    </td>


                    <?php if (isset($_SESSION['permisos']['permiso_crear_productos']) && $_SESSION['permisos']['permiso_crear_productos'] == 1) { ?>
                        <td>
                            <a class="link_delete"
                                href="eliminar_confirmar_producto.php?id=<?php echo $data['codproducto']; ?>"><i
                                    class="fa-solid fa-trash"></i> Eliminar</a>
                        </td>
                    <?php } ?>
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