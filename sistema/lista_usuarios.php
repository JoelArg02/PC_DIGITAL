<?php
session_start();
if (!isset($_SESSION['permisos']['permiso_ver_usuarios']) || $_SESSION['permisos']['permiso_ver_usuarios'] != 1) {
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
	<title>Lista Usuarios</title>
	<link href="css/lista_usuarios.css" rel="stylesheet" type="text/css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/popup.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
	<script src="js/validacion_usuario.js"></script>

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

		<h1>Lista de usuarios</h1>

		<?php if (isset($_SESSION['permisos']['permiso_crear_usuarios']) && $_SESSION['permisos']['permiso_crear_usuarios'] == 1) { ?>

			<a href="registro_usuario.php" class="btn_new"
				onclick="loadPopupContentFromLink(this.href); return false;">Nuevo Usuario</a>
			<a href="edit_user.php" class="btn_new" onclick="loadPopupContentFromLink(this.href); return false;">Editar
				Usuario</a>
		<?php } ?>
		<?php if (isset($_SESSION['idUser']) && $_SESSION['idUser'] == 1) { ?>
				<a href="rol.php" class="btn_new" onclick="loadPopupContentFromLink(this.href); return false;">Rol personalizado</a>
		<?php } ?>


		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Correo</th>
				<th>Usuario</th>
				<th>Cargo</th>
				<?php if (isset($_SESSION['permisos']['permiso_crear_usuarios']) && $_SESSION['permisos']['permiso_crear_usuarios'] == 1) { ?>

					<th>Acciones</th>

				<?php } ?>
			</tr>
			<?php

			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM usuario WHERE estatus = 1 ");
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

			$query = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.cargo
			FROM usuario u 
			INNER JOIN rol r ON u.rol = r.idrol 
			WHERE estatus = 1 
			ORDER BY idusuario ASC 
			LIMIT $desde, $por_pagina");

			mysqli_close($conection);
			$result = mysqli_num_rows($query);
			if ($result > 0) {
				while ($data = mysqli_fetch_array($query)) {
					?>
					<tr>
						<td>
							<?php echo $data['idusuario']; ?>
						</td>
						<td>
							<?php echo $data['nombre']; ?>
						</td>
						<td>
							<?php echo $data['correo']; ?>
						</td>
						<td>
							<?php echo $data['usuario']; ?>
						</td>

						<td>
							<?php echo $data['cargo'] ?>
						</td>
						<?php if (isset($_SESSION['permisos']['permiso_crear_usuarios']) && $_SESSION['permisos']['permiso_crear_usuarios'] == 1) { ?>

							<td>

								<a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $data['idusuario']; ?>"><i
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