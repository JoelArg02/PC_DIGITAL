<?php
session_start();
include "../../conexion.php";

if (!isset($_SESSION['permisos']['permiso_crear_productos']) || $_SESSION['permisos']['permiso_crear_productos'] != 1) {
    header("location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $producto = $_POST['producto'];


    $id_measurement = $_POST['id_measurement'];

    $medida = $_POST['medida'];
    $proveedor = $_POST['proveedor'];
    $precio = $_POST['precio'];
    $contenedores = $_POST['contenedores'];
    $cajasPorContenedor = $_POST['cajasPorContenedor'];
    $unidadesPorCaja = $_POST['unidadesPorCaja'];
    $usuario_id = $_SESSION['idUser'];

    if ($id_measurement === "nueva_medida") {
        $nuevaMedida = $_POST['nueva_medida'];
        $query_insert_measurement = "INSERT INTO product_measurement (measurement) VALUES ('$nuevaMedida')";
        $result_insert_measurement = mysqli_query($conection, $query_insert_measurement);

        if (!$result_insert_measurement) {
            $_SESSION['popup_message'] = 'Error al insertar la nueva medida: ' . mysqli_error($conection);
            header("location: ../lista_producto.php");
            exit();
        }

        $inserted_measurement_id = mysqli_insert_id($conection); // Obtener el ID de la nueva medida
    } else {
        $inserted_measurement_id = $id_measurement;
    }

    if (empty($producto) || empty($medida) || empty($proveedor) || empty($precio) || empty($unidadesPorCaja) || $precio <= 0 || $unidadesPorCaja <= 0) {
        $_SESSION['popup_message'] = 'Todos los campos son necesarios.';
        header("location: ../lista_producto.php");

    } else {
        $totalUnidades = $contenedores * $cajasPorContenedor * $unidadesPorCaja;
        $query_insert = "INSERT INTO producto (descripcion, medida, proveedor, precio, existencia, usuario_id) VALUES ('$producto', '$medida', '$proveedor', '$precio', '$totalUnidades', '$usuario_id')";
        $result = mysqli_query($conection, $query_insert);

        if ($result) {
            $_SESSION['popup_message'] = 'Inserción exitosa.';
            header("location: ../lista_producto.php");

        } else {
            $_SESSION['popup_message'] = 'Error al guardar el producto, intente de nuevo o contacte al administrador del sistema';
            header("location: ../lista_producto.php");

        }
    }

    header("location: ../lista_producto.php");
    exit();
}
?>