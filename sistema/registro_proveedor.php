<?php
session_start();
if (!isset($_SESSION['permisos']['permiso_crear_proveedor']) || $_SESSION['permisos']['permiso_crear_proveedor'] != 1) {
	header("location: index.php");
	exit();
}
include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Proveedor</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />

</head>


<body>
	<section id="container-2">
		<span class="close-button" onclick="closePopup()">&times;</span>


		<div class="form_register">
			<h1>Registro Proveedor</h1>
			<div class="alert">
				<?php
				echo isset($alert) ? $alert : '';
				echo isset($_SESSION['popup_message']) ? '<p class="msg_info" id="popupMessage">' . $_SESSION['popup_message'] . '</p>' : '';
				unset($_SESSION['popup_message']);
				?>
			</div>
			<form id="registroForm" action="process/process_register_supplier.php" method="post">
				<label for="proveedor">Proveedor: </label>
				<input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor" onchange="validar()">
				<div id="mensajeErrorProveedor" class="mensaje-error"></div>


				<label for="cedula">Cédula: </label>
				<input type="text" name="cedula" id="cedula" placeholder="Número de cédula" onchange="validar()" required>
				<div id="mensajeErrorCedula" class="mensaje-error"></div>


				<label for="contacto">Nombre y Apellido: </label>
				<input type="text" id="contacto" name="contacto" onchange="validar()" placeholder="Nombre Apellido" required>
				<div id="mensajeErrorNombre" class="mensaje-error"></div>

				<label for="telefono">Teléfono: </label>
				<input type="text" name="telefono" id="telefono" onchange="validar()" placeholder="0981515127" required>
				<div id="mensajeErrorTelefono" class="mensaje-error"></div>


				<label for="correo">Correo electrónico: </label>
				<input type="email" name="correo" id="correo" placeholder="texto@dominio.com" onchange="validar()" required>
				<div id="mensajeErrorCorreo" class="mensaje-error"></div>

				<label for="direccion">Dirección: </label>
				<input type="text" name="direccion" id="direccion" onchange="validar()" placeholder="Dirección completa">
				<div id="mensajeErrorDireccion" class="mensaje-error"></div>


				<div class="button-container">

					<input type="submit" name="submit" value="Registrar" id="btn_sb" class="btn_save">
				</div>
			</form>
		</div>


	</section>

</body>

</html>