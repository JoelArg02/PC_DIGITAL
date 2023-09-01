<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_ver_reportes']) || $_SESSION['permisos']['permiso_ver_reportes'] != 1) {
	header("location: index.php");
	exit();
}
$first_date = $_GET['first_date'] ?? '';
$second_date = $_GET['second_date'] ?? '';
$first_date_mysql = date('Y-m-d H:i:s', strtotime($first_date));
$second_date_mysql = date('Y-m-d H:i:s', strtotime($second_date));

?>


<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Reporte Cliente</title>
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
			<input type="submit" value="Generar Reporte">
		</form>

		<table>
			<tr>
				<th>Cliente ID</th>
				<th>Primer Nombre</th>
				<th>Nombre Anterior</th>
				<th>Nuevo Nombre</th>
				<th>Primera Cedula</th>
				<th>Cedula Anterior</th>
				<th>Nueva Cedula</th>
				<th>Primer Telefono</th>
				<th>Telefono Anterior</th>
				<th>Nuevo Telefono</th>
				<th>Primera Direccion</th>
				<th>Direccion Anterior</th>
				<th>Nueva Direccion</th>
				<th>Fecha de Cambio</th>
			</tr>
			<?php
			$query = mysqli_query($conection, "SELECT
		   c.id_cliente,
		   c.nombre AS primer_nombre,
		   lu.old_name AS nombre_anterior,
		   lu.new_name AS nuevo_nombre,
		   c.cedula AS primera_cedula,
		   lu.old_cedula AS cedula_anterior,
		   lu.new_cedula AS nueva_cedula,
		   c.telefono AS primer_telefono,
		   lu.old_tel AS telefono_anterior,
		   lu.new_tel AS nuevo_telefono,
		   c.direccion AS primera_direccion,
		   lu.old_dir AS direccion_anterior,
		   lu.new_dir AS nueva_direccion,
		   lu.dateadd AS fecha_cambio
	   FROM
		   cliente c
	   LEFT JOIN
		   client_log_update lu ON c.id_cliente = lu.id_client
	   WHERE
		   lu.dateadd BETWEEN '$first_date_mysql' AND '$second_date_mysql'  
	   ORDER BY
		   c.id_cliente, lu.dateadd");

			while ($data = mysqli_fetch_array($query)) {
			?>
				<tr>
					<td>
						<?php echo $data['id_cliente']; ?>
					</td>
					<td>
						<?php echo $data['primer_nombre']; ?>
					</td>
					<td>
						<?php echo $data['nombre_anterior']; ?>
					</td>
					<td>
						<?php echo $data['nuevo_nombre']; ?>
					</td>
					<td>
						<?php echo $data['primera_cedula']; ?>
					</td>
					<td>
						<?php echo $data['cedula_anterior']; ?>
					</td>
					<td>
						<?php echo $data['nueva_cedula']; ?>
					</td>
					<td>
						<?php echo $data['primer_telefono']; ?>
					</td>
					<td>
						<?php echo $data['telefono_anterior']; ?>
					</td>
					<td>
						<?php echo $data['nuevo_telefono']; ?>
					</td>
					<td>
						<?php echo $data['primera_direccion']; ?>
					</td>
					<td>
						<?php echo $data['direccion_anterior']; ?>
					</td>
					<td>
						<?php echo $data['nueva_direccion']; ?>
					</td>
					<td>
						<?php echo $data['fecha_cambio']; ?>
					</td>
				</tr>
			<?php
			}
			?>

		</table>

		<div class="paginador">
			<ul>
				<?php

				$total_registros = mysqli_num_rows($query);
				$registros_por_pagina = 10; // Ajusta esto según el número de registros que quieras mostrar por página
				$total_paginas = ceil($total_registros / $registros_por_pagina);

				// Obtén el número de página actual (si está configurado en la URL)
				$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
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