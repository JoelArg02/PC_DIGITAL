<?php
session_start();
include "../conexion.php";
if (
	!isset($_SESSION['permisos']['permiso_ver_proveedores']) || $_SESSION['permisos']['permiso_ver_proveedores'] != 1
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
	<title>Lista de proveedores</title>
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
	<link rel="stylesheet" type="text/css" href="css/style_tables.css">

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


		<h1>Lista de proveedores</h1>
		<a href="lista_proveedor.php" class="btn_new">Volver</a>

		<form action="buscar_proveedor.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Proveedor</th>
				<th>Contacto</th>
				<th>Teléfono</th>
				<th>Dirección</th>
				<th>Fecha</th>
			</tr>
			<?php

			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM proveedor WHERE (id_supplier LIKE '%busqueda%' OR proveedor LIKE '%busqueda%' OR contacto LIKE '%busqueda%' OR telefono LIKE '%busqueda%') AND estatus = 1 ");

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

			$query = mysqli_query($conection, "SELECT * FROM proveedor WHERE 
								(id_supplier LIKE '%$busqueda%' OR 
								proveedor LIKE '%$busqueda%' OR 
								contacto LIKE '%$busqueda%' OR 
								telefono LIKE '%$busqueda%') 
								AND 
								estatus = 1 ORDER BY id_supplier ASC LIMIT $desde, $por_pagina");

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
							<?php echo $data['telefono']; ?>
						</td>
						<td>
							<?php echo $data['direccion']; ?>
						</td>
						<td>
							<?php echo $fecha->format('d-m-Y'); ?>
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