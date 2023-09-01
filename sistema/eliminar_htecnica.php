<?php

session_start();
if (!isset($_SESSION['permisos']['permiso_crear_hoja_tecnica']) || $_SESSION['permisos']['permiso_crear_hoja_tecnica'] != 1) {
    header("location: index.php");
    exit();
}
$id = intval($_GET['id']);

if (empty($id)) {
    header('location: lista_htecnica.php');
}

include "../conexion.php";

$query_delete = mysqli_query($conection, "DELETE FROM rule_recipe WHERE id_recipe = $id");
$query_delete = mysqli_query($conection, "DELETE FROM recipe WHERE id = $id");

header('location: lista_htecnica.php');