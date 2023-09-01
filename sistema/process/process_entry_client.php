<?php

session_start();
if (!isset($_SESSION['permisos']['permiso_crear_clientes']) || $_SESSION['permisos']['permiso_crear_clientes'] != 1) {
    header("location: index.php");
    exit();
}
include "../../conexion.php";

function getOldAndNewValuesCliente($conection, $id_cliente, $cedula, $nombre, $telefono, $direccion)
{

    $query_cliente = mysqli_query($conection, "SELECT * FROM cliente WHERE id_cliente = $id_cliente");
    $result_cliente = mysqli_fetch_assoc($query_cliente);
    if (!$query_cliente) {

    }
    echo "<script>";
    echo "console.log('Retrieved data:', " . json_encode($result_cliente) . ");";

    $old_cedula = $result_cliente['cedula'];
    $new_cedula = $cedula;
    $old_nombre = $result_cliente['nombre'];
    $new_nombre = $nombre;
    $old_telefono = $result_cliente['telefono'];
    $new_telefono = $telefono;
    $old_direccion = $result_cliente['direccion'];
    $new_direccion = $direccion;

    return array(
        'old_cedula' => $old_cedula,
        'new_cedula' => $new_cedula,
        'old_nombre' => $old_nombre,
        'new_nombre' => $new_nombre,
        'old_telefono' => $old_telefono,
        'new_telefono' => $new_telefono,
        'old_direccion' => $old_direccion,
        'new_direccion' => $new_direccion
    );
}

if (!empty($_POST)) {
    $alert = '';
    $id_cliente = $_POST['id_cliente'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $usuario_id = $_SESSION['idUser'];
    $values = getOldAndNewValuesCliente($conection, $id_cliente, $cedula, $nombre, $telefono, $direccion);

    $changes = array();

    if ($values['new_cedula'] != $values['old_cedula']) {
        $changes['cedula'] = array(
            'anterior' => $values['old_cedula'],
            'nuevo' => $values['new_cedula']
        );
    } else {
        $changes['cedula'] = array(
            'anterior' => $values['old_cedula'],
            'nuevo' => $values['old_cedula']
        );
    }

    if ($values['new_nombre'] != $values['old_nombre']) {
        $changes['nombre'] = array(
            'anterior' => $values['old_nombre'],
            'nuevo' => $values['new_nombre']
        );
    } else {
        $changes['nombre'] = array(
            'anterior' => $values['old_nombre'],
            'nuevo' => $values['old_nombre']
        );
    }

    if ($values['new_telefono'] != $values['old_telefono']) {
        $changes['telefono'] = array(
            'anterior' => $values['old_telefono'],
            'nuevo' => $values['new_telefono']
        );
    } else {
        $changes['telefono'] = array(
            'anterior' => $values['old_telefono'],
            'nuevo' => $values['old_telefono']
        );
    }

    if ($values['new_direccion'] != $values['old_direccion']) {
        $changes['direccion'] = array(
            'anterior' => $values['old_direccion'],
            'nuevo' => $values['new_direccion']
        );
    } else {
        $changes['direccion'] = array(
            'anterior' => $values['old_direccion'],
            'nuevo' => $values['old_direccion']
        );
    }


    if (!empty($changes)) {
        $changes_json = json_encode($changes);

        $query_audit = mysqli_query($conection, "INSERT INTO client_log_update (id_client, old_cedula, new_cedula, old_name, new_name, old_tel, new_tel, old_dir, new_dir, usuario_id) VALUES ('$id_cliente', '{$values['old_cedula']}', '{$values['new_cedula']}', '{$values['old_nombre']}', '{$values['new_nombre']}', '{$values['old_telefono']}', '{$values['new_telefono']}', '{$values['old_direccion']}', '{$values['new_direccion']}', '$usuario_id')");


    }

    $query_update = mysqli_query($conection, "UPDATE cliente SET cedula='$cedula', nombre='$nombre', telefono='$telefono', direccion='$direccion', usuario_id='$usuario_id' WHERE id_cliente = $id_cliente");

    if ($query_update) {
        $_SESSION['popup_message'] = 'El cliente editado exitosamente.';
        header("location: ../lista_clientes.php");
    } else {
        $_SESSION['popup_message'] = 'Error al editar el cliente, intente de nuevo o contacte al administrador del sistema';
        header("location: ../lista_clientes.php");
    }
}

?>