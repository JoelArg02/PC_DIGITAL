<?php
session_start();
include "../conexion.php";

if (!isset($_SESSION['permisos']['permiso_ver_reportes']) || $_SESSION['permisos']['permiso_ver_reportes'] != 1) {
    header("location: index.php");
    exit();
}$first_date = $_GET['first_date'] ?? '';
$second_date = $_GET['second_date'] ?? '';

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Reporte Producto</title>
	<link rel="stylesheet" type="text/css" href="css/style_tables.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />


</head>

<body>
	<?php include "includes/header.php"; ?>

	<style>
		.date-fields-container {
			display: flex;
			align-items: center;
		}

		.date-fields-container label,
		.date-fields-container input {
			margin-right: 10px;
		}

		.IO {
			position: fixed;
			bottom: 10px;
			left: 265px;
			border: 2px solid #eb4d4b;
			background: linear-gradient(to right, rgb(243, 37, 51), rgb(179, 0, 75));
			padding: 5px 10px;
		}

		.BT_IO {
			color: white;
			text-decoration: none;
		}
	</style>
	<section id="container">
		<form method="get" action="">
			<div class="date-fields-container">
				<div>
					<h4>Desde</h4>
					<label for="first_date">Fecha de Inicio:</label>
					<input type="datetime-local" name="first_date" id="first_date" value="<?php echo $first_date; ?>">
				</div>
				<div>
					<h4>Hasta</h4>
					<label for="second_date">Fecha de Fin:</label>
					<input type="datetime-local" name="second_date" id="second_date" value="<?php echo $second_date; ?>">
				</div>
			</div>
			<input type="submit" value="Buscar">
			<div class="IO">
				<a class="BT_IO" target="_blank" href="factura/reporte_InventarioProducto.php?first_date=<?php echo urlencode($first_date); ?>&second_date=<?php echo urlencode($second_date); ?>">Generar Reporte</a>
			</div>

		</form>
		

		<table>
			<tr>
				<th>Nombre</th>
				<th>Precio Actual</th>
				<th>Promedio Precio</th>
				<th>Stock</th>
				<th>Tendencia</th>
			</tr>
			<?php

			$sql_registre = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM product_i ");
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

			$where_condition = '';

			if (!empty($first_date) && !empty($second_date)) {
				// Convierte las fechas a formato válido para la consulta
				$formatted_first_date = date('Y-m-d H:i:s', strtotime($first_date));
				$formatted_second_date = date('Y-m-d H:i:s', strtotime($second_date));
				$where_condition = "WHERE p.date_add BETWEEN '$formatted_first_date' AND '$formatted_second_date'";
			}

			$query = mysqli_query($conection, "SELECT p.*, AVG(l.new_price) as average_price, COUNT(r.id_product_rule) as tendencia FROM product_i p
                                    LEFT JOIN product_log_update l ON p.id_producto = l.producto_id
                                    LEFT JOIN rule_recipe r ON p.id_producto = r.id_product_rule
                                    $where_condition
                                    GROUP BY p.id_producto
                                    ORDER BY p.id_producto ASC LIMIT $desde, $por_pagina");


			while ($data = mysqli_fetch_array($query)) {
				$product_id = $data['id_producto'];

				$avg_query = mysqli_query($conection, "SELECT AVG(new_price) as average_price FROM product_log_update WHERE producto_id = $product_id ");
				$avg_data = mysqli_fetch_array($avg_query);
				$average_price = $avg_data['average_price'];

			?>
				<tr>
					<td>
						<?php echo $data['name']; ?>
					</td>
					<td>
						<?php echo $data['price']; ?>
					</td>
					<td>
						<?php
						if ($average_price > 0) {
							echo number_format($average_price, 2);
						} else {
							echo "No hay cambios";
						}
						?>
					</td>
					<td>
						<?php echo $data['stock']; ?>
					</td>
					<td>
						<?php echo $data['tendencia']; ?>
					</td>
				</tr>
			<?php
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

</body>

</html>