<?php
session_start();
if (!isset($_SESSION['permisos']['permiso_crear_productos']) || $_SESSION['permisos']['permiso_crear_productos'] != 1) {
    header("location: index.php");
    exit();
}
include "../conexion.php";

$query_producto = mysqli_query($conection, "SELECT * FROM producto");
$query_proveedor = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
$producto_info = array();

$query_info = mysqli_query($conection, "SELECT codproducto, precio, proveedor FROM producto");
while ($info = mysqli_fetch_assoc($query_info)) {
    $producto_info[$info['codproducto']] = array('precio' => $info['precio'], 'proveedor' => $info['proveedor']);
}

mysqli_close($conection);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ingreso a Bodega</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/jpg" href="img/favicon.png" />
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>

</head>

<body>
    <section id="container-2">
        <div class="form_register">
            <h1>Ingreso a bodega</h1>
            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <span class="close-button" onclick="closePopup()">&times;</span>

            <form id="entryForm" action="process/process_entry_product.php" method="post">
                <input type="hidden" name="codproducto" id="codproducto" value="">
                <label for="producto">Producto: </label>
                <select name="producto" id="producto" class="select2" required onchange="populateFieldsProductos()">
                    <option value="" disabled selected>Selecciona un producto</option>
                    <?php
                    while ($producto = mysqli_fetch_array($query_producto)) {
                        echo '<option value="' . $producto['codproducto'] . '">' . $producto['descripcion'] . '</option>';
                    }
                    ?>
                </select>

                <label for="medida">Medida </label>
                <input type="text" name="medida" id="medida" placeholder="Medida del producto" required>

                <label for="proveedor">Proveedor</label>
                <select name="proveedor" id="proveedor" required>
                    <option value="" disabled selected>Selecciona un proveedor</option>
                    <?php
                    while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                        echo '<option value="' . $proveedor['id_supplier'] . '">' . $proveedor['proveedor'] . '</option>';
                    }
                    ?>
                </select>
                <label for="precio">Precio: </label>
                <input type="number" name="precio" id="precio" step="0.01" min="0" required>

                <label for="contenedores">Cantidad de Contenedores</label>
                <input type="number" name="contenedores" id="contenedores" placeholder="Contenedores" min="1" value="1"
                    required>

                <label for="cajasPorContenedor">Cajas por Contenedor</label>
                <input type="number" name="cajasPorContenedor" id="cajasPorContenedor"
                    placeholder="Cajas por Contenedor" min="1" value="1" required>

                <label for="unidadesPorCaja">Unidades por Caja</label>
                <input type="number" name="unidadesPorCaja" id="unidadesPorCaja" placeholder="Unidades por Caja"
                    value="0" min="0" required>

                <div class="button-container">
                    <input type="submit" name="submit" value="Enviar" class="btn_save">
                </div>
            </form>
        </div>
    </section>

</body>

</html>