<?php
$host = 'citaviso.com';
$user = 'u900659423_citaviso';
$password = 'Pdigi2019@';
$db = 'u900659423_citaviso';

$conection = @mysqli_connect($host, $user, $password, $db);

if (!$conection) {
    die("Error en la conexión: ");
}

?>
