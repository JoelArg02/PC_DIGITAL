<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_clientes']) || $_SESSION['permisos']['permiso_crear_clientes'] != 1) {
    header("location: index.php");
    exit();
}
$query_cliente = mysqli_query($conection, "SELECT * FROM cliente");
$cliente_info = array();

$query_info = mysqli_query($conection, "SELECT id_cliente, nombre, cedula, telefono, direccion FROM cliente");
while ($info = mysqli_fetch_assoc($query_info)) {
    $cliente_info[$info['id_cliente']] = array('nombre' => $info['nombre'], 'cedula' => $info['cedula'], 'telefono' => $info['telefono'], 'direccion' => $info['direccion']);
}

mysqli_close($conection);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/jpg" href="img/favicon.png" />

</head>

<body>
    <section id="container-2">
        <div class="form_register">
            <h1>Editar cliente</h1>

            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <span class="close-button" onclick="closePopup()">&times;</span>

            <form id="entryForm" action="process/process_entry_client.php" method="post">
                <input type="hidden" name="id_cliente" id="id_cliente" value="">
                <label for="cliente">Cliente: </label>
                <select name="cliente" id="cliente" required onchange="populateFieldsClientes()">
                    <option value="" disabled selected>Selecciona un cliente</option>
                    <?php
                    while ($cliente = mysqli_fetch_array($query_cliente)) {
                        echo '<option value="' . $cliente['id_cliente'] . '">' . $cliente['nombre'] . '</option>';
                    }
                    ?>
                </select>
                <label for="nombre">Nombre: </label>
                <input type="text" id="nombre" name="nombre" placeholder="Dos nombres y dos apellidos" required onchange="validarCliente()">
				<div id="mensajeErrorNombre" class="mensaje-error"></div>

                <input type="hidden" name="cedula" id="cedula" placeholder="Ingrese su número de cédula" onchange="validarCliente()">

                <label for="telefono">Teléfono: </label>
                <input type="text" name="telefono" id="telefono" placeholder="Ingrese su número celular" onchange="validarCliente()">
				<div id="mensajeErrorTelefono" class="mensaje-error"></div>

                <label for="direccion">Dirección: </label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección completa" onchange="validarCliente()">
				<div id="mensajeErrorDireccion" class="mensaje-error"></div>

                <div class="button-container">
                    <input type="submit" name="submit" value="Guardar Cambios" class="btn_save">
                </div>
            </form>
        </div>
    </section>
</body>

</html>