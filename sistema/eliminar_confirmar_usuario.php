<?php
	session_start();
	if (!isset($_SESSION['permisos']['permiso_crear_usuarios']) || $_SESSION['permisos']['permiso_crear_usuarios'] != 1) {
		header("location: index.php");
		exit();
	}
	include "../conexion.php";
	

	if(!empty($_POST)){

		if($_POST['idusuario']==1){
			header("location: lista_usuarios.php");
			mysqli_close($conection);
				exit;
		}

		$idusuario = $_POST['idusuario'];
		$query_delete = mysqli_query($conection, "UPDATE usuario SET estatus=0 WHERE idusuario=$idusuario");
		mysqli_close($conection);
		if($query_delete){
			header("location: lista_usuarios.php");
		}else{
			echo "Error al eliminar";
		}
	}

	if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1){
		header("location: lista_usuarios.php");
		mysqli_close($conection);
	}else{
		$idusuario = $_REQUEST['id'];
		$query = mysqli_query($conection, "SELECT u.nombre, u.usuario, u.cargo, u.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario = $idusuario ");
		mysqli_close($conection);
		$result = mysqli_num_rows($query);

		if($result > 0){
			while ($data = mysqli_fetch_array($query)){
				$nombre = $data['nombre'];
				$usuario = $data['usuario'];
				$cargo = $data['cargo'];
				$rol = $data['rol'];
			}
		}else{
			header("location: lista_usuarios.php");
		}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>ELiminar usuario</title>
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
</head>

<style>
	form{
		margin: auto;
		padding: 20px 50px;
		border: 0;
	}

	input, select{
		display: block;
		width: 100%;
		font-size: 13pt;
		padding: 5px;
		border: 1px solid #85929e;
		border-radius: 5px;
	}

	.link_delete{
		color: #f26b6b;
	}

	.data_delete{
		text-align: center;
	}

	.data_delete h2{
		font-size: 12pt;
	}

	.data_delete span{
		font-weight:  bold;
		color: #4f72d4;
		font-size: 12pt;
	}

	.btn_cancel, .btn_ok{
		width: 124px;
		background: #478ba2;
		color: #fff;
		display: inline-block;
		padding: 5px;
		border-radius: 5px;
		cursor: pointer;
		margin: 15px;
	}

	.btn_cancel{
		background: #42b343;
	}
</style>


<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Nombre: <span><?php echo $nombre; ?></span></p>
			<p>Usuario: <span><?php echo $usuario; ?></span></p>
			<p>Tipo Usuario: <span><?php echo $rol; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
				<a href="lista_usuarios.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Aceptar" class="btn_ok">
			</form>
		</div>
	</section>
</body>
</html>