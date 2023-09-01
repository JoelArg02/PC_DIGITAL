<?php
session_start();
include "../../conexion.php";

if (!isset($_SESSION['permisos']['permiso_crear_usuarios']) || $_SESSION['permisos']['permiso_crear_usuarios'] != 1) {
    header("location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['idusuario']; // id del usuario
    $selectedRoles = isset($_POST['selectedRoles']) ? $_POST['selectedRoles'] : array(); // Obtén los roles seleccionados en un array
    $rol = $_POST['rol']; // id del rol de la tabla rol
    $permiso_crear_usuarios = in_array("Crear usuarios", $selectedRoles) ? 1 : 0;
    $permiso_ver_usuarios = in_array("Ver usuarios", $selectedRoles) ? 1 : 0;
    $permiso_ver_proveedores = in_array("Ver proveedores", $selectedRoles) ? 1 : 0;
    $permiso_crear_proveedor = in_array("Crear proveedor", $selectedRoles) ? 1 : 0;
    $permiso_ver_productos = in_array("Ver productos", $selectedRoles) ? 1 : 0;
    $permiso_crear_productos = in_array("Crear productos", $selectedRoles) ? 1 : 0;
    $permiso_agregar_productos = in_array("Agregar productos", $selectedRoles) ? 1 : 0;
    $permiso_crear_hoja_tecnica = in_array("Crear hoja tecnica", $selectedRoles) ? 1 : 0;
    $permiso_ver_hojas_tecnicas = in_array("Ver hojas tecnicas", $selectedRoles) ? 1 : 0;
    $permiso_ver_ordenes = in_array("Ver ordenes", $selectedRoles) ? 1 : 0;
    $permiso_crear_ordenes = in_array("Crear ordenes", $selectedRoles) ? 1 : 0;
    $permiso_ver_clientes = in_array("Ver clientes", $selectedRoles) ? 1 : 0;
    $permiso_crear_clientes = in_array("Crear clientes", $selectedRoles) ? 1 : 0;
    $permiso_ver_reportes = in_array("Ver reportes", $selectedRoles) ? 1 : 0;

    // Consulta SQL para actualizar los permisos
    $query_update_rol = "UPDATE rol SET 
        permiso_crear_usuarios = $permiso_crear_usuarios,
        permiso_ver_usuarios = $permiso_ver_usuarios,
        permiso_ver_proveedores = $permiso_ver_proveedores,
        permiso_crear_proveedor = $permiso_crear_proveedor,
        permiso_ver_productos = $permiso_ver_productos,
        permiso_crear_productos = $permiso_crear_productos,
        permiso_agregar_productos = $permiso_agregar_productos,
        permiso_crear_hoja_tecnica = $permiso_crear_hoja_tecnica,
        permiso_ver_hojas_tecnicas = $permiso_ver_hojas_tecnicas,
        permiso_ver_ordenes = $permiso_ver_ordenes,
        permiso_crear_ordenes = $permiso_crear_ordenes,
        permiso_ver_clientes = $permiso_ver_clientes,
        permiso_crear_clientes = $permiso_crear_clientes,
        permiso_ver_reportes = $permiso_ver_reportes
        WHERE idrol = $rol";

    $result_update_rol = mysqli_query($conection, $query_update_rol);

    if ($result_update_rol) {
        $_SESSION['popup_message'] = 'Actualización de roles exitosa.';
    } else {
        $_SESSION['popup_message'] = 'Error al actualizar roles: ' . mysqli_error($conection);
    }

    header("location: ../lista_usuarios.php");
    exit();
}

mysqli_close($conection);
?>
