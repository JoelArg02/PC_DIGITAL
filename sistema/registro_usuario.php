<?php
session_start();
if (!isset($_SESSION['permisos']['permiso_crear_usuarios']) || $_SESSION['permisos']['permiso_crear_usuarios'] != 1) {
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
	<title>Registro Usuario</title>
	<link rel="stylesheet" type="text/css" href="css/popup.css">
	<link rel="icon" type="image/jpg" href="img/favicon.png" />
</head>

<body>
	<section id="container-2">
		<span class="close-button" onclick="closePopup()">&times;</span>

		<div class="form_register">
			<h1>Registro usuario</h1>

			<div class="alert">
				<?php echo isset($alert) ? $alert : ''; ?>
			</div>
			<form id="registroForm" action="process/process_register_user.php" method="post">

				<label for="nombre">Nombre </label>
				<input type="text" id="nombre" name="nombre" placeholder="Nombre Apellido" title="El formato debe ser 'Nombre Apellido'" onchange="validar()" required>
				<div id="mensajeErrorNombre" class="mensaje-error"></div>


				<label for="correo">Correo electrónico </label>
				<input type="email" name="correo" id="correo" placeholder="texto@dominio.com" onchange="validar()" title="Ingresa un correo con dominio .com o .net válido" required>
				<div id="mensajeErrorCorreo" class="mensaje-error"></div>

				<label for="usuario">Usuario </label>
				<input type="text" name="usuario" id="usuario" placeholder="usuario" pattern="^[a-z]+$" title="Ingresa un usuario válido con solo minúsculas" onchange="validar()" required>
				<div id="mensajeErrorUsuario" class="mensaje-error"></div>


				<label for="clave">Clave </label>
				<input type="password" name="clave" id="clave" placeholder="Clave de acceso" onchange="validar()" title="La clave debe tener al menos 8 caracteres, incluir una mayúscula y un carácter especial" required>
				<div id="mensajeErrorClave" class="mensaje-error"></div>

				<label for="cargo">Cargo </label>
				<select name="cargo" id="cargo" title="Selecciona un cargo" required>
					<option value="" disabled selected>Selecciona un cargo</option>
					<?php if (isset($_SESSION['idUser']) && $_SESSION['idUser'] == 1) { ?>
						<option value="Superadmin">Superadmin</option>
					<?php } ?>
					<option value="Gerente">Gerente</option>
					<option value="Vendedor">Vendedor</option>
					<option value="Almacenero">Almacenero</option>
					<option value="Contador">Contador</option>
				</select>

				<div class="button-container">
					<input type="submit" name="submit" value="Crear Usuario" id="btn_sb" class="btn_save">
				</div>
			</form>
		</div>
	</section>
</body>

</html>