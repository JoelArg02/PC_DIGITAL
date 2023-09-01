<?php
session_start();
include "../../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_proveedor']) || $_SESSION['permisos']['permiso_crear_proveedor'] != 1) {
    header("location: index.php");
    exit();
}

$alert = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proveedor = $_POST['proveedor'];
    $cedula = $_POST['cedula'];
    $contacto = $_POST['contacto'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $usuario_id = $_SESSION['idUser'];

    if (empty($cedula) || empty($proveedor) || empty($contacto) || empty($telefono) || empty($correo) || empty($direccion)) {
        $_SESSION['popup_message'] = 'Complete todos los campos';
        header("location: ../lista_proveedor.php");
    } else {
        $query_check = "SELECT * FROM proveedor WHERE cedula ='$cedula' AND contacto = '$contacto' AND telefono = '$telefono' AND correo='$correo'";
        $result_check = mysqli_query($conection, $query_check);

        if (mysqli_num_rows($result_check) > 0) {
            $_SESSION['popup_message'] = 'Ya existe un registro con el mismo proveedor, contacto y número de teléfono.';
        } else {
            $query_insert = "INSERT INTO proveedor(cedula, proveedor, contacto, correo, telefono, direccion, usuario_id) 
                             VALUES('$cedula', '$proveedor', '$contacto', '$correo', '$telefono', '$direccion', '$usuario_id')";
            $result = mysqli_query($conection, $query_insert);

            if ($result) {
                $_SESSION['popup_message'] = 'Inserción exitosa.';
            } else {
                $error_message = 'Error al guardar proveedor, reintente de nuevo o contacte con el administrador.';
                $_SESSION['popup_message'] = $error_message;
            }
        }
        header("location: ../lista_proveedor.php");
    }
    mysqli_close($conection);
}

echo $alert;
?>