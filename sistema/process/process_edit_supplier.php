<?php
session_start();
include "../../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_proveedor']) || $_SESSION['permisos']['permiso_crear_proveedor'] != 1) {
	header("location: ../index.php");
	exit();
}
$alert = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id_supplier = $_POST['id_supplier'];
	$proveedor = $_POST['proveedor'];
	$contacto = $_POST['contacto'];
	$telefono = $_POST['telefono'];
	$direccion = $_POST['direccion'];
	$cedula = $_POST['cedula'];
	$correo = $_POST['correo'];

	echo "<script>console.log('Se recibio el update :D');</script>";


	$query_check = "SELECT * FROM proveedor WHERE cedula ='$cedula' AND contacto = '$contacto' AND telefono = '$telefono' AND correo='$correo'";
	$result_check = mysqli_query($conection, $query_check);

	if (mysqli_num_rows($result_check) > 0) {
		$_SESSION['popup_message'] = 'Ya existe un registro con el mismo proveedor, contacto y número de teléfono.';
		header("location: ../lista_proveedor.php");

	} else { 
		$sql_update = mysqli_query($conection, "UPDATE proveedor SET proveedor = '$proveedor', correo = '$correo'
	, contacto='$contacto', telefono='$telefono', direccion='$direccion', cedula = '$cedula' WHERE id_supplier = $id_supplier ");

		if ($sql_update) {
			$_SESSION['popup_message'] = 'Actualización exitosa.';
			header("location: ../lista_proveedor.php");

		} else {
			$_SESSION['popup_message'] = 'Error, intente de nuevo o contacte al administrador.' . mysqli_error($conection);
			header("location: ../lista_proveedor.php");
		}
		mysqli_close($conection);

	}
	echo $alert;

}