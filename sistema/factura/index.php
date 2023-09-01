<?php

if ($_SESSION['rol'] != 1) {
	if ($_SESSION['rol'] == 3) {
		header("location: ../index.php");
	} elseif ($_SESSION['rol'] == 2) {
		header("location: ../index.php");
	} else {
		header("location: ../index.php");
	}
}

?>