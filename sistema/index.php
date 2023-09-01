<?php
	session_start();
	include "includes/session_timeout.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<link rel="icon" type="image/jpg" href="img/favicon.png"/>
	<link rel="stylesheet" href="css/estiloindex.css" type="text/css">
	<title>Sistema Ventas</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<img src="img/favicon.png" alt="logo de la empresa citaviso">
		<h1>Bienvenido al sistema de Citaviso!</h1>
		<h3>Entraste con la cuenta "<?php echo $_SESSION['nombre'] ?>"</h3>
	</section>
</body>
</html>