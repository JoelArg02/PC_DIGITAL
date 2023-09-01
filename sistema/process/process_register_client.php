<?php
session_start();
include "../../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_clientes']) || $_SESSION['permisos']['permiso_crear_clientes'] != 1) {
    header("location: index.php");
    exit();
}

if (!empty($_POST)) {

    $alert = '';

    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $_SESSION['popup_message'] = 'Complete todos los campos';
        header("location: ../lista_clientes.php");
    } else {

        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $usuario_id = $_SESSION['idUser'];

        if (!isValidCI($cedula)) {
            $_SESSION['popup_message'] = 'El numero de cedula no es valido.';
            header("location: ../lista_clientes.php");

        } else {

            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE cedula = '$cedula' ");
            $result = mysqli_fetch_array($query);

            if ($result > 0) {
                $_SESSION['popup_message'] = 'El usuario ya existe.';
                header("location: ../lista_clientes.php");

                
            } else {
                $query_insert = mysqli_query($conection, "INSERT INTO cliente(cedula,nombre,telefono,direccion,usuario_id) VALUES('$cedula','$nombre','$telefono','$direccion','$usuario_id')");

                if ($query_insert) {
                    $_SESSION['popup_message'] = 'Insercion exitosa';
                    header("location: ../lista_clientes.php");

                } else {
                    $_SESSION['popup_message'] = 'Error al guardar';
                    header("location: ../lista_clientes.php");

                }
                header("location: ../lista_clientes.php");

            }
        }
    }
    mysqli_close($conection);
}

function isValidCI($ci)
{
    if (!is_numeric($ci)) {
        return false;
    }

    if (strlen($ci) !== 10) {
        return false;
    }

    $total = 0;
    $coeficientes = array(2, 1, 2, 1, 2, 1, 2, 1, 2);

    for ($i = 0; $i < 9; $i++) {
        $temp = $ci[$i] * $coeficientes[$i];
        if ($temp > 9) {
            $temp -= 9;
        }
        $total += $temp;
    }

    $verificador = 10 - ($total % 10);
    if ($verificador === 10) {
        $verificador = 0;
    }

    return $ci[9] == $verificador;
}

?>