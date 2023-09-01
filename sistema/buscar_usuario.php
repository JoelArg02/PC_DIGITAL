<?php
session_start();
include "../conexion.php";
include "includes/session_timeout.php";
if (
	!isset($_SESSION['permisos']['permiso_ver_usuarios']) || $_SESSION['permisos']['permiso_ver_usuarios'] != 1 ||
	!isset($_SESSION['permisos']['permiso_crear_usuarios']) || $_SESSION['permisos']['permiso_crear_usuarios'] != 1
) {
	header("location: index.php");
	exit();
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista Usuarios</title>
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
	<link rel="stylesheet" type="text/css" href="css/style_tables.css">
</head>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<?php

		$busqueda = strtolower($_REQUEST['busqueda']);
		if (empty($busqueda)) {
			header('location: lista_usuarios.php');
			mysqli_close($conection);
		}

		?>


		<h1>Lista de usuarios</h1>
		<a href="lista_usuarios.php" class="btn_new">Volver</a>

		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
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


			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM usuario WHERE (idusuario LIKE '%busqueda%' OR nombre LIKE '%busqueda%' OR correo LIKE '%busqueda%' OR usuario LIKE '%busqueda%') AND estatus = 1 ");
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

			$query = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.cargo FROM usuario u INNER JOIN rol r ON u.rol = r.idrol 
								WHERE 
								(u.idusuario LIKE '%$busqueda%' OR 
								u.nombre LIKE '%$busqueda%' OR 
								u.correo LIKE '%$busqueda%' OR 
								u.usuario LIKE '%$busqueda%' OR
								u.cargo LIKE '%$busqueda%') 
								AND 
								estatus = 1 ORDER BY u.usuario ASC LIMIT $desde, $por_pagina");

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
							<?php echo $data['cargo']; ?>
						</td>
						<td>
							<?php echo $data['cargo'] ?>
						</td>
					
					</tr>

					<?php
				}
			}
			?>

		</table>

		<?php

		if ($total_registro != 0) {
			?>

			<div class="paginador">
				<ul>
					<?php
					if ($pagina != 1) {
						?>
						<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<< /a>
						</li>
						<li><a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>">
								<<< /a>
						</li>
						<?php
					}
					for ($i = 1; $i <= $total_paginas; $i++) {
						if ($i == $pagina) {
							echo '<li class="pageSelected">' . $i . '</li>';
						} else {
							echo '<li><a href="?pagina=' . $i . '&busqueda=' . $busqueda . '">' . $i . '</a></li>';
						}
					}

					if ($pagina != $total_paginas) {
						?>
						<li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
						<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
	</section>
</body>

</html>