<?php
session_start();

include "../../conexion.php";

if (!isset($_SESSION['permisos']['permiso_crear_usuarios']) || $_SESSION['permisos']['permiso_crear_usuarios'] != 1) {
    header("location: index.php");
    exit();
}

$alert = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['idusuario'];
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $user = $_POST['usuario'];
    $clave = md5($_POST['clave']);
    $cargo = $_POST['cargo'];
    $rol = $_POST['rol'];
    $estatus = $_POST['estatus'];
    if (isset($_POST['estatus'])) {
        $estatus = true;
    } else {
        $estatus = false;
    }

    $selectedRoles = array();

    if ($cargo === 'Superadmin') {
        $selectedRoles = array("Crear usuarios", "Ver usuarios", "Ver proveedores", "Crear proveedor", "Ver productos", "Crear productos", "Agregar productos", "Crear hoja técnica", "Ver hojas técnicas", "Ver órdenes", "Crear órdenes", "Ver clientes", "Crear clientes", "Ver reportes");
    } elseif ($cargo === 'Gerente') {
        $selectedRoles = array("Ver usuarios", "Ver reportes", "Ver proveedores", "Ver productos", "Ver órdenes", "Ver clientes");
    } elseif ($cargo === 'Vendedor') {
        $selectedRoles = array("Ver clientes", "Ver productos", "Crear clientes", "Ver reportes", "Ver hojas técnicas", "Crear hoja técnica", "Ver órdenes", "Crear órdenes");
    } elseif ($cargo === 'Almacenero') {
        $selectedRoles = array("Ver productos", "Crear productos", "Agregar productos");
    } elseif ($cargo === 'Contador') {
        $selectedRoles = array("Ver reportes");
    } else {
        $selectedRoles = array();
    }

    if ($cargo === "") {
        $_SESSION['popup_message'] = 'Error al crear usuario, no se recibió el cargo.';
        header("location: ../lista_usuarios.php");
        exit();
    }

    $permiso_crear_usuarios = in_array("Crear usuarios", $selectedRoles) ? 1 : 0;
    $permiso_ver_usuarios = in_array("Ver usuarios", $selectedRoles) ? 1 : 0;
    $permiso_ver_proveedores = in_array("Ver proveedores", $selectedRoles) ? 1 : 0;
    $permiso_crear_proveedor = in_array("Crear proveedor", $selectedRoles) ? 1 : 0;
    $permiso_ver_productos = in_array("Ver productos", $selectedRoles) ? 1 : 0;
    $permiso_crear_productos = in_array("Crear productos", $selectedRoles) ? 1 : 0;
    $permiso_agregar_productos = in_array("Agregar productos", $selectedRoles) ? 1 : 0;
    $permiso_crear_hoja_tecnica = in_array("Crear hoja técnica", $selectedRoles) ? 1 : 0;
    $permiso_ver_hojas_tecnicas = in_array("Ver hojas técnicas", $selectedRoles) ? 1 : 0;
    $permiso_ver_ordenes = in_array("Ver órdenes", $selectedRoles) ? 1 : 0;
    $permiso_crear_ordenes = in_array("Crear órdenes", $selectedRoles) ? 1 : 0;
    $permiso_ver_clientes = in_array("Ver clientes", $selectedRoles) ? 1 : 0;
    $permiso_crear_clientes = in_array("Crear clientes", $selectedRoles) ? 1 : 0;
    $permiso_ver_reportes = in_array("Ver reportes", $selectedRoles) ? 1 : 0;


    $query_update_rol = "UPDATE rol SET permiso_crear_usuarios = '$permiso_crear_usuarios', permiso_ver_usuarios = '$permiso_ver_usuarios', permiso_ver_proveedores = '$permiso_ver_proveedores', permiso_crear_proveedor = '$permiso_crear_proveedor', permiso_ver_productos = '$permiso_ver_productos', permiso_crear_productos = '$permiso_crear_productos', permiso_agregar_productos = '$permiso_agregar_productos', permiso_crear_hoja_tecnica = '$permiso_crear_hoja_tecnica', permiso_ver_hojas_tecnicas = '$permiso_ver_hojas_tecnicas', permiso_ver_ordenes = '$permiso_ver_ordenes', permiso_crear_ordenes = '$permiso_crear_ordenes', permiso_ver_clientes = '$permiso_ver_clientes', permiso_crear_clientes = '$permiso_crear_clientes', permiso_ver_reportes = '$permiso_ver_reportes' WHERE idrol = $rol";

    $result_update_rol = mysqli_query($conection, $query_update_rol);

    if ($result_update_rol) {

        $query_update_usuario = "UPDATE usuario SET nombre = '$nombre', correo = '$email', usuario = '$user', clave = '$clave', cargo = '$cargo', estatus = '$estatus' WHERE idusuario = $usuario_id";
        $result_update_usuario = mysqli_query($conection, $query_update_usuario);

        if ($result_update_usuario) {
            $_SESSION['popup_message'] = 'Actualización exitosa.';
            header("location: ../lista_usuarios.php");
        } else {
            $_SESSION['popup_message'] = 'Error al actualizar usuario: ' . mysqli_error($conection);
            header("location: ../lista_usuarios.php");

        }
    } else {
        $_SESSION['popup_message'] = 'Error al actualizar rol: ' . mysqli_error($conection);
        header("location: ../lista_usuarios.php");

    }

    mysqli_close($conection);
}

echo $alert;
?>