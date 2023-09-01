<?php

session_start();
if (!isset($_SESSION['permisos']['permiso_crear_productos']) || $_SESSION['permisos']['permiso_crear_productos'] != 1) {
	header("location: index.php");
	exit();
}
include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Registro Producto</title>
	<link rel="stylesheet" href="css/popup.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
	<section id="container-2">
		<span class="close-button" onclick="closePopup()">&times;</span>

		<div class="form_register">

			<div class="alert">
				<?php echo isset($alert) ? $alert : ''; ?>
			</div>

			<form id="registroForm" action="process/process_register_product.php" method="post">
				<h1>Registro Producto</h1>

				<label for="producto">Producto </label>
				<input type="text" name="producto" id="producto" placeholder="Nombre del producto" required>

				<label for="medida">Medida </label>
				<input type="text" name="medida" id="medida" placeholder="Medida del producto" required>

				<label for="proveedor">Proveedor</label>
				<select name="proveedor" id="proveedor" class="select2" required>
					<?php
					$query_proveedor = mysqli_query($conection, "SELECT id_supplier, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
					while ($proveedor = mysqli_fetch_array($query_proveedor)) {
						echo '<option value="' . $proveedor['id_supplier'] . '">' . $proveedor['proveedor'] . '</option>';
					}
					mysqli_close($conection);
					?>
				</select>

				<label for="precio">Precio </label>
				<input type="number" name="precio" id="precio" step="0.01" placeholder="Precio del producto" min="0"
					required>

				<label for="contenedores">Cantidad de Contenedores</label>
				<input type="number" name="contenedores" id="contenedores" placeholder="Contenedores" min="1" value="1"
					required>

				<label for="cajasPorContenedor">Cajas por Contenedor</label>
				<input type="number" name="cajasPorContenedor" id="cajasPorContenedor"
					placeholder="Cajas por Contenedor" min="1" value="1" required>

				<label for="unidadesPorCaja">Unidades por Caja</label>
				<input type="number" name="unidadesPorCaja" id="unidadesPorCaja" placeholder="Unidades por Caja"
					value="1" min="1" required>

				<div class="button-container">
					<input type="submit" name="submit" value="Registrar" class="btn_save">
				</div>
			</form>
		</div>
	</section>
	<script>
		$(document).ready(function () {
			$('.select2').select2();
		});
	</script>
</body>

</html>