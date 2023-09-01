<?php
require_once(__DIR__ . '/functions.php');

if (!isset($_SESSION)) {
	session_start();
}

if (empty($_SESSION['active'])) {
	header('location: ../');
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
	.user-profile-popup {
		position: absolute;
		top: calc(100% + 3px);
		right: 0;
		background-color: #fff;
		border: 1px solid #ccc;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		padding: 10px;
		z-index: 1000;
		display: none;
	}

	.user-image-container {
		position: relative;
		display: inline-block;
	}

	.user-profile-content {
		text-align: center;
	}

	.user-profile-content h2 {
		margin: 0;
	}

	.user-profile-content p {
		margin: 5px 0;
	}

	.user-profile-content a {
		text-decoration: none;
		color: black;
	}
</style>

<header>
	<div class="header">
		<h1>Citaviso</h1>
		<div class="optionsBar">
			<p>Cargo: <?php echo $_SESSION['cargo']?>   | </p>
			<p>Ecuador, <?php echo fechaC(); ?></p>
			<span>|</span>
			<div class="user-image-container">
				<img class="photouser" src="img/user.png" alt="Usuario" id="userImage">
				<div class="user-profile-popup" id="userProfilePopup">
					<div class="user-profile-content">
						<h2 style="color: black;">Administrador</h2>
						<p style="color: black;">Nombre: Nombre del Administrador</p>
						<p style="color: black;">Correo: admin@example.com</p>
						<a href="#">Cerrar sesión</a>
					</div>
				</div>
			</div>
			<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
		</div>
	</div>
	<?php include "nav.php"; ?>
</header>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const userImage = document.querySelector("#userImage");
		const userProfilePopup = document.querySelector("#userProfilePopup");

		userImage.addEventListener("click", function() {
			userProfilePopup.style.display = "block";
		});

		userProfilePopup.addEventListener("click", function(event) {
			if (event.target === userProfilePopup) {
				userProfilePopup.style.display = "none";
			}
		});
	});
</script>