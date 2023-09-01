<?php
session_start();

include "../../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_productos']) || $_SESSION['permisos']['permiso_crear_productos'] != 1) {
    header("location: index.php");
    exit();
}
function getOldAndNewValues($conection, $medida, $codproducto, $precio, $proveedor, $cantidad)
{
    $query_producto = mysqli_query($conection, "SELECT * FROM producto WHERE codproducto = $codproducto");
    $result_producto = mysqli_fetch_assoc($query_producto);

    $old_medida = $result_producto['medida'];
    $new_medida = $medida;

    $old_price = $result_producto['precio'];
    $new_price = $precio;

    $old_supplier = $result_producto['proveedor'];
    $new_supplier = $proveedor;

    $old_stock = $result_producto['existencia'];
    $new_stock = $old_stock + $cantidad;

    return array(
        'old_medida' => $old_medida,
        'new_medida' => $new_medida,
        'old_price' => $old_price,
        'new_price' => $new_price,
        'old_supplier' => $old_supplier,
        'new_supplier' => $new_supplier,
        'old_stock' => $old_stock,
        'new_stock' => $new_stock
    );
}



if (!empty($_POST)) {
    $alert = '';

    $codproducto = $_POST['codproducto'];
    $proveedor = $_POST['proveedor'];
    $precio = $_POST['precio'];
    $contenedores = $_POST['contenedores'];
    $cajasPorContenedor = $_POST['cajasPorContenedor'];
    $unidadesPorCaja = $_POST['unidadesPorCaja'];
    $medida = $_POST['medida'];
    $usuario_id = $_SESSION['idUser'];
    $values = getOldAndNewValues($conection, $medida, $codproducto, $precio, $proveedor, $cantidad);

    $changes = array();

    if ($values['new_price'] != $values['old_price']) {
        $changes['precio'] = array(
            'anterior' => $values['old_price'],
            'nuevo' => $values['new_price']
        );
    } else {
        $changes['precio'] = array(
            'anterior' => $values['old_price'],
            'nuevo' => $values['old_price']
        );
    }

    if ($values['new_supplier'] != $values['old_supplier']) {
        $changes['proveedor'] = array(
            'anterior' => $values['old_supplier'],
            'nuevo' => $values['new_supplier']
        );
    } else {
        $changes['proveedor'] = array(
            'anterior' => $values['old_supplier'],
            'nuevo' => $values['old_supplier']
        );
    }

    if ($values['new_medida'] != $values['old_medida']) {
        $changes['medida'] = array(
            'anterior' => $values['old_medida'],
            'nuevo' => $values['new_medida']
        );
    } else {
        $changes['medida'] = array(
            'anterior' => $values['old_medida'],
            'nuevo' => $values['old_medida']
        );
    }



    if ($values['new_stock'] != $values['old_stock']) {
        $changes['stock'] = array(
            'anterior' => $values['old_stock'],
            'nuevo' => $values['new_stock']
        );
    } else {
        $changes['stock'] = array(
            'anterior' => $values['old_stock'],
            'nuevo' => $values['old_stock']
        );
    }

    if (!empty($changes)) {
        $changes_json = json_encode($changes);
        $query_audit = mysqli_query($conection, "INSERT INTO product_log_update (producto_id, usuario_id,cambios, old_medida , new_medida ,old_price, new_price, old_supplier, new_supplier, old_stock, new_stock) VALUES ('$codproducto', '$usuario_id', '$changes_json', '{$values['old_medida']}', '{$values['new_medida']}' ,'{$values['old_price']}', '{$values['new_price']}', '{$values['old_supplier']}', '{$values['new_supplier']}', '{$values['old_stock']}', '{$values['new_stock']}')");
        if (!$query_audit) {
            $_SESSION['popup_message'] = 'Edicion Exitosa.';
            header("location: ../lista_producto.php");

        }
    }
    $totalUnidades = $contenedores * $cajasPorContenedor * $unidadesPorCaja;

    $query_update = mysqli_query($conection, "UPDATE producto SET medida='$medida', proveedor='$proveedor', precio ='$precio', usuario_id= '$usuario_id', existencia = existencia + '$totalUnidades'  WHERE  codproducto = $codproducto");

    if ($query_update) {
        $_SESSION['popup_message'] = 'Inserción exitosa.';
        header("location: ../lista_producto.php");
    } else {
        $_SESSION['popup_message'] = 'Inserción error.';
        header("location: ../lista_producto.php");

    }
}

?>