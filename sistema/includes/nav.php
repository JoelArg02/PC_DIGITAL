<?php
?>
<nav>
	<ul>
		<li><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>

		<?php if ($_SESSION['permisos']['permiso_ver_usuarios'] || $_SESSION['permisos']['permiso_crear_usuarios']) { ?>
			<li class="principal">
				<a href="lista_usuarios.php"><i class="fa-solid fa-user"></i> Usuarios</a>
			</li>
		<?php } ?>

		<?php if ($_SESSION['permisos']['permiso_ver_proveedores'] || $_SESSION['permisos']['permiso_ver_proveedores']) { ?>
			<li class="principal">
				<a href="lista_proveedor.php"><i class="fa-solid fa-truck-field"></i> Proveedores</a>
			</li>
		<?php } ?>

		<?php if ($_SESSION['permisos']['permiso_ver_productos'] || $_SESSION['permisos']['permiso_crear_productos']) { ?>
			<li class="principal">
				<a href="lista_producto.php"><i class="fa-solid fa-box-open"></i> Productos</a>
			</li>
		<?php } ?>

		<?php if ($_SESSION['permisos']['permiso_ver_hojas_tecnicas'] || $_SESSION['permisos']['permiso_crear_hoja_tecnica']) { ?>
			<li class="principal">
				<a href="lista_htecnica.php"><i class="fa-solid fa-clipboard-list"></i> Hoja Técnica</a>
				<ul>
					<li><a href="lista_htecnica.php"><i class="fa-solid fa-list"></i> Lista</a></li>
					<?php if ($_SESSION['permisos']['permiso_crear_hoja_tecnica']) { ?>
						<li><a href="registro_htecnica.php"><i class="fa-solid fa-plus"></i> Crear</a></li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>

		<?php if ($_SESSION['permisos']['permiso_ver_ordenes'] || $_SESSION['permisos']['permiso_crear_ordenes']) { ?>
			<li class="principal">
				<a href="lista_ordenes.php"><i class="fa-solid fa-arrow-up-wide-short"></i> Órdenes</a>
				<ul>
					<li><a href="lista_ordenes.php"><i class="fa-solid fa-list"></i> Lista de órdenes</a></li>
					<?php if ($_SESSION['permisos']['permiso_crear_ordenes']) { ?>
						<li><a href="crear_orden.php"><i class="fa-solid fa-plus"></i> Crear</a></li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>

		<?php if ($_SESSION['permisos']['permiso_ver_clientes'] || $_SESSION['permisos']['permiso_crear_clientes']) { ?>
			<li class="principal">
				<a href="lista_clientes.php"><i class="fa-solid fa-person"></i> Clientes</a>
			</li>
		<?php } ?>

		<?php if ($_SESSION['permisos']['permiso_ver_reportes']) { ?>
			<li class="principal">

				<a href="#"><i class="fa-solid fa-person"></i> Reportes</a>
				<ul>
					<li><a href="inventario_original.php">Inventario inicial completo</a></li>
					<li><a href="report_product.php">Reporte Producto</a></li>
					<li><a href="report_cliente.php">Reporte Cliente</a></li>
				</ul>
			</li>

		<?php } ?>

	</ul>
</nav>