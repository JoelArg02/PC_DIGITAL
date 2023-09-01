<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_ver_clientes']) || $_SESSION['permisos']['permiso_ver_clientes'] != 1) {
	header("location: index.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista Clientes</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="css/style_tables.css">
	<link rel="stylesheet" type="text/css" href="css/popup.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
	<script src="js/validacion_cliente.js"></script>


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

		<h1>Lista de Clientes </h1>
		<?php if (isset($_SESSION['permisos']['permiso_crear_clientes']) && $_SESSION['permisos']['permiso_crear_clientes'] == 1) { ?>
			<a href="registro_cliente.php" class="btn_new"
				onclick="loadPopupContentFromLink(this.href); return false;">Nuevo Cliente</a>
				<a href="ingreso_cliente.php" class="btn_new"
				onclick="loadPopupContentFromLink(this.href); return false;">Editar Cliente</a>
		
		<?php } ?>
		<form action="buscar_cliente.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Cédula</th>
				<th>Nombre</th>
				<th>Teléfono</th>
				<th>Dirección</th>
				<?php if (isset($_SESSION['permisos']['permiso_crear_clientes']) && $_SESSION['permisos']['permiso_crear_clientes'] == 1) { ?>
				<th>Acciones</th>
				<?php } ?>
			</tr>
			<?php

			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM cliente WHERE estatus = 1 ");
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

			$query = mysqli_query($conection, "SELECT * FROM cliente WHERE estatus = 1 ORDER BY id_cliente ASC LIMIT $desde,$por_pagina");

			mysqli_close($conection);
			$result = mysqli_num_rows($query);
			if ($result > 0) {
				while ($data = mysqli_fetch_array($query)) {
					?>
					<tr>
						<td>
							<?php echo $data['id_cliente']; ?>
						</td>
						<td>
							<?php echo $data['cedula']; ?>
						</td>
						<td>
							<?php echo $data['nombre']; ?>
						</td>
						<td>
							<?php echo $data['telefono']; ?>
						</td>
						<td>
							<?php echo $data['direccion']; ?>
						</td>
						<?php if (isset($_SESSION['permisos']['permiso_crear_clientes']) && $_SESSION['permisos']['permiso_crear_clientes'] == 1) { ?>

						<td>
							
								<a class="link_delete"
									href="eliminar_confirmar_cliente.php?id=<?php echo $data['id_cliente']; ?>"><i
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
					<li><a href="?pagina=1">&laquo;&laquo;</a></li>
					<li><a href="?pagina=<?php echo $pagina - 1; ?>">&laquo;</a></li>
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
					<li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?> ">>|</a></li>
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