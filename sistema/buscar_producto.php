<?php
session_start();
include "../conexion.php";
include "includes/session_timeout.php";

if (
	!isset($_SESSION['permisos']['permiso_ver_productos']) || $_SESSION['permisos']['permiso_ver_productos'] != 1
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
	<link rel="stylesheet" type="text/css" href="css/style_tables.css">
	<title>Lista Productos</title>
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
</head>

<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<?php

		$busqueda = strtolower($_REQUEST['busqueda']);
		if (empty($busqueda)) {
			header('location: lista_proveedor.php');
			mysqli_close($conection);
		}

		?>

		<h1>Lista de productos</h1>
		<a href="lista_producto.php" class="btn_new">Volver</a>

		<form action="buscar_producto.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" class="btn_search">
		</form>

		<table>
			<tr>
				<th>Código</th>
				<th>Nombre producto</th>
				<th>Proveedor</th>
				<th>Precio</th>
				<th>Stock</th>

			</tr>
			<?php

			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM producto WHERE (proveedor LIKE '%busqueda%' LIKE '%busqueda%' OR descripcion LIKE '%busqueda%' OR precio LIKE '%busqueda%' OR existencia LIKE '%busqueda%') AND estatus = 1 ");
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

			$query = mysqli_query($conection, "SELECT * FROM producto WHERE 
								(codproducto LIKE '%$busqueda%' OR 
								descripcion LIKE '%$busqueda%' OR 
								proveedor LIKE '%$busqueda%' OR
								precio LIKE '%$busqueda%' OR 
								existencia LIKE '%$busqueda%') 
								AND 
								estatus = 1 ORDER BY codproducto ASC LIMIT $desde, $por_pagina");

			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			if ($result > 0) {
				while ($data = mysqli_fetch_array($query)) {

					?>
					<tr>
						<td>
							<?php echo $data['codproducto']; ?>
						</td>
						<td>
							<?php echo $data['descripcion']; ?>
						</td>
						<td>
							<?php echo $data['proveedor']; ?>
						</td>
						<td>
							<?php echo $data['precio']; ?>
						</td>
						<td>
							<?php echo $data['existencia']; ?>

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
					<li><a href="?pagina=<?php echo 1; ?>">|<< /a>
					</li>
					<li><a href="?pagina=<?php echo $pagina - 1; ?>">
							<<< /a>
					</li>
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
</body>

</html>