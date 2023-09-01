<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_proveedor']) || $_SESSION['permisos']['permiso_crear_proveedor'] != 1) {
    header("location: index.php");
    exit();
}
$query_cliente = mysqli_query($conection, "SELECT * FROM proveedor");
$cliente_info = array();

$query_info = mysqli_query($conection, "SELECT id_supplier, cedula, proveedor, contacto, correo, telefono, direccion FROM proveedor");
while ($info = mysqli_fetch_assoc($query_info)) {
    $cliente_info[$info['id_supplier']] = array(
        'cedula' => $info['cedula'],
        'proveedor' => $info['proveedor'],
        'contacto' => $info['contacto'],
        'correo' => $info['correo'],
        'telefono' => $info['telefono'],
        'direccion' => $info['direccion']
    );
}

mysqli_close($conection);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/jpg" href="img/favicon.png" />
    <script src="js/popup.js"></script>

</head>

<body>
    <section id="container-2">

        <div class="form_register">
            <h1>Actualizar proveedor</h1>

            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <span class="close-button" onclick="closePopup()">&times;</span>
            <form id="entryForm" action="process/process_edit_supplier.php" method="post">
                <input type="hidden" name="id_supplier" id="id_supplier" value="">
                <label for="proveedor">Selecciona un proveedor: </label>
                <select name="selectedProveedorId" id="selectedProveedorId" required onchange="populateFieldsSupplier()">
                    <option value="" disabled selected>Selecciona un proveedor</option>
                    <?php
                    while ($proveedor = mysqli_fetch_array($query_cliente)) {
                        echo '<option value="' . $proveedor['id_supplier'] . '">' . $proveedor['proveedor'] . '</option>';
                    }
                    ?>
                </select>

                <label for="proveedor">Proveedor: </label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor"
                    onchange="validar()">
                <div id="mensajeErrorProveedor" class="mensaje-error"></div>

                <label for="contacto">Nombre y Apellido: </label>
                <input type="text" name="contacto" id="contacto" onchange="validar()" placeholder="Nombre Apellido">
                <div id="mensajeErrorNombre" class="mensaje-error"></div>

                <input type="hidden" name="cedula" id="cedula" placeholder="Número de cédula" onchange="validar()"
                    required>

                <label for="email">Correo electrónico: </label>
                <input type="email" name="correo" id="correo" placeholder="texto@dominio.com" onchange="validar()">
                <div id="mensajeErrorCorreo" class="mensaje-error"></div>

                <label for="telefono">Teléfono: </label>
                <input type="text" name="telefono" id="telefono" onchange="validar()" placeholder="0981515127">
                <div id="mensajeErrorTelefono" class="mensaje-error"></div>

                <label for="direccion">Dirección: </label>
                <input type="text" name="direccion" id="direccion" onchange="validar()"
                    placeholder="Dirección completa">
                <div id="mensajeErrorDireccion" class="mensaje-error"></div>

                <input type="submit" value="Actualizar proveedor" id="btn_sb" class="btn_save">
            </form>
    </section>
</body>

</html>