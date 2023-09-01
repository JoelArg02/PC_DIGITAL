<?php
session_start();
include '../conexion.php';



if (!empty($_POST)) {

	if ($_POST['action'] == 'getProducts') {
		$id = intval($_POST['id'] ?? 0);
		$query = mysqli_query($conection, "SELECT * FROM producto " . ($id > 0 ? "WHERE codproducto = $id" : ""));
		mysqli_close($conection);
		$result = mysqli_num_rows($query);
		$products = [];

		if ($result > 0) {
			while ($row = mysqli_fetch_assoc($query)) {
				$products[] = $row;
			}
		}

		echo json_encode($products, JSON_UNESCAPED_UNICODE);
		exit;
	}

	if ($_POST['action'] == 'getClientes') {
		$id_cliente = intval($_POST['id_cliente'] ?? 0);
		$query = mysqli_query($conection, "SELECT * FROM cliente " . ($id_cliente > 0 ? "WHERE id_cliente = $id_cliente" : ""));

		mysqli_close($conection);
		$result = mysqli_num_rows($query);
		$clientes = [];

		if ($result > 0) {
			while ($row = mysqli_fetch_assoc($query)) {
				$clientes[] = $row;
			}
		}

		echo json_encode($clientes, JSON_UNESCAPED_UNICODE);
		exit;
	}

	if ($_POST['action'] == 'infoProducto') {
		$producto_id = $_POST['producto'];

		$query = mysqli_query($conection, "SELECT codproducto, descripcion, precio, existencia FROM producto WHERE codproducto = $producto_id AND estatus = 1");

		mysqli_query($conection);

		$result = mysqli_num_rows($query);
		if ($result > 0) {
			$data = mysqli_fetch_assoc($query);
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
			exit;
		}
		echo 'error';
		exit;
	}



	if ($_POST['action'] == 'searchCliente') {
		if (!empty($_POST['cliente'])) {
			$cedula = $_POST['cliente'];

			$query = mysqli_query($conection, "SELECT * FROM cliente WHERE cedula LIKE '$cedula' AND estatus = 1 ");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			$data = '';

			if ($result > 0) {
				$data = mysqli_fetch_assoc($query);
			} else {
				$data = 0;
			}
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		exit;

	}


	// Buscar Producto
	if ($_POST['action'] == 'searchProduct') {
		if (!empty($_POST['producto'])) {
			$producto = $_POST['producto'];

			$query = mysqli_query($conection, "SELECT * FROM producto WHERE descripcion LIKE '%$producto%' AND estatus = 1 ");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			$data = '';

			if ($result > 0) {
				$data = mysqli_fetch_assoc($query);
			} else {
				$data = 0;
			}
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		exit;

	}

	//Extraer datos usuarios

	if ($_POST['action'] == 'getUsers') {
		if (!empty($_POST['idusuario'])) {

			$idusuario = $_POST['idusuario'];

			$query = mysqli_query($conection, "SELECT * FROM usuario " . ($idusuario > 0 ? "WHERE idusuario = $idusuario" : ""));
			$result = mysqli_num_rows($query);

			if ($result > 0) {
				$data = mysqli_fetch_assoc($query);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			} else {
				echo json_encode(0);
			}

			mysqli_close($conection);
		}
	}

	if($_POST['action'] == 'getSupplier') {
		if (!empty($_POST['id_supplier'])) {

			$id_supplier = $_POST['id_supplier'];

			$query = mysqli_query($conection, "SELECT * FROM proveedor " . ($id_supplier > 0 ? "WHERE id_supplier = $id_supplier" : ""));
			$result = mysqli_num_rows($query);

			if ($result > 0) {
				$data = mysqli_fetch_assoc($query);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			} else {
				echo json_encode(0);
			}

			mysqli_close($conection);
		}
	}

}

exit;

?>