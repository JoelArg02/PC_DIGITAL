<?php
session_start();
if (!isset($_SESSION['permisos']['permiso_ver_proveedores']) || $_SESSION['permisos']['permiso_ver_proveedores'] != 1) {
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
	<title>Lista Proveedores</title>
	<link rel="stylesheet" type="text/css" href="css/popup.css">
	<link rel="stylesheet" type="text/css" href="css/style_tables.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script src="js/validacion_proveedor.js">


	</script>
</head>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="alert">
			<?php
			echo isset($alert) ? $alert : '';
			echo isset($_SESSION['popup_message']) ? '<p class="msg_info" id="popupMessage">' . $_SESSION['popup_message'] . '</p>' : '';
			unset($_SESSION['popup_message']);
			?>
		</div>

		<script>
			setTimeout(function () {
				var popupMessage = document.getElementById("popupMessage");
				if (popupMessage) {
					popupMessage.style.display = "none";
				}
			}, 4000);
		</script>

		<h1>Lista de proveedores</h1>
		<?php if (isset($_SESSION['permisos']['permiso_crear_proveedor']) && $_SESSION['permisos']['permiso_crear_proveedor'] == 1) { ?>

			<a href="registro_proveedor.php" class="btn_new"
				onclick="loadPopupContentFromLink(this.href); return false;">Nuevo Proveedor</a>
			<a href="edit_supplier.php" class="btn_new" onclick="loadPopupContentFromLink(this.href); return false;">Editar
				Proveedor</a>
		<?php } ?>

		<form action="buscar_proveedor.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Proveedor</th>
				<th>Contacto</th>
				<th>Cedula</th>
				<th>Teléfono</th>
				<th>Dirección</th>
				<th>Fecha</th>
				<?php if (isset($_SESSION['permisos']['permiso_crear_proveedor']) && $_SESSION['permisos']['permiso_crear_proveedor'] == 1) { ?>

				<th>Acciones</th>

				<?php } ?>
			</tr>
			<?php

			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM proveedor WHERE estatus = 1 ");
			$result_registre = mysqli_fetch_array($sql_registre);
			$total_registro = $result_registre['total_registro'];

			$por_pagina = 10;

			if (empty($_GET['pagina'])) {
				$pagina = 1;
			} else {
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina - 1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY id_supplier ASC LIMIT $desde,$por_pagina");

			mysqli_close($conection);
			$result = mysqli_num_rows($query);
			if ($result > 0) {
				while ($data = mysqli_fetch_array($query)) {

					$formato = 'Y-m-d H:i:s';
					$fecha = DateTime::createFromFormat($formato, $data['date_add']);
					?>
					<tr>
						<td>
							<?php echo $data['id_supplier']; ?>
						</td>
						<td>
							<?php echo $data['proveedor']; ?>
						</td>
						<td>
							<?php echo $data['contacto']; ?>
						</td>
						<td>
							<?php echo $data['cedula']; ?>
						<td>
							<?php echo $data['telefono']; ?>
						</td>
						<td>
							<?php echo $data['direccion']; ?>
						</td>
						<td>
							<?php echo $fecha->format('d-m-Y'); ?>
						</td>
						<?php if (isset($_SESSION['permisos']['permiso_crear_proveedor']) && $_SESSION['permisos']['permiso_crear_proveedor'] == 1) { ?>

						<td>
							<a class="link_delete"
								href="eliminar_confirmar_proveedor.php?id=<?php echo $data['id_supplier']; ?>"><i
									class="fa-solid fa-trash"></i> Eliminar</a>
						</td>
						<?php } ?>
					</tr>

					<?php
				}
			}
			?>

		</table>

		<div class="paginador">
			<ul>
				<?php
				if ($pagina != 1) {
					?>
					<li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i></a></li>
					<li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fa-solid fa-backward"></i></a></li>
					<?php
				}
				for ($i = 1; $i <= $total_paginas; $i++) {
					if ($i == $pagina) {
						echo '<li class="pageSelected">' . $i . '</li>';
					} else {
						echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
					}
				}

				if ($pagina != $total_paginas) {
					?>
					<li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fa-solid fa-forward"></i></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?> "><i class="fa-solid fa-forward-step"></i></a></li>
				<?php } ?>
			</ul>
		</div>

	</section>

	<div class="popup-container" id="popupContainer">
		<div class="popup-content" id="popupContent">

		</div>
		<span class="close-button" onclick="closePopup()">&times;</span>
	</div>
	<div class="overlay" id="overlay" onclick="closePopup()"></div>

</body>

</html>