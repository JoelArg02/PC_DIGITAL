<?php
session_start();
include "../conexion.php";
if (isset($_SESSION['idUser']) && $_SESSION['idUser'] != 1) {
    header("location: index.php");
    exit();
}
$query_cliente = mysqli_query($conection, "SELECT * FROM usuario");
$cliente_info = array();

$query_info = mysqli_query($conection, "SELECT idusuario, nombre, correo, usuario, cargo, rol FROM usuario");
while ($info = mysqli_fetch_assoc($query_info)) {
    $cliente_info[$info['idusuario']] = array('nombre' => $info['nombre'], 'correo' => $info['correo'], 'usuario' => $info['usuario'], 'cargo' => $info['cargo'], 'rol' => $info['rol']);
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
    <script src="js/popup.js"></script>

</head>

<body>
    <section id="container-2">
        <div class="form_register">
            <h1>Dar Privilegios</h1>

            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <span class="close-button" onclick="closePopup()">&times;</span>

            <form id="entryForm" action="process/process_entry_rol.php" method="post">
                <input type="hidden" name="idusuario" id="idusuario" value="">
                <input type="hidden" name="rol" id="rol" value="">


                <label for="usuario">Selecciona un usuario: </label>
                <select name="selectedUsuarioId" id="selectedUsuarioId" required onchange="populateFieldsUsers()">
                    <option value="" disabled selected>Selecciona un usuario</option>
                    <?php
                    while ($usuario = mysqli_fetch_array($query_cliente)) {
                        echo '<option value="' . $usuario['idusuario'] . '">' . $usuario['usuario'] . '</option>';
                    }
                    ?>
                </select>
                <label for="roles">Selecciona los roles: </label>
                <label>Selecciona los roles:</label><br>
                <input type="checkbox" name="selectedRoles[]" value="Ver usuarios">Ver usuarios<br>
                <input type="checkbox" name="selectedRoles[]" value="Crear usuarios">Crear usuarios<br>
                <input type="checkbox" name="selectedRoles[]" value="Ver proveedores">Ver proveedores<br>
                <input type="checkbox" name="selectedRoles[]" value="Crear proveedor">Crear proveedor<br>
                <input type="checkbox" name="selectedRoles[]" value="Ver productos">Ver productos<br>
                <input type="checkbox" name="selectedRoles[]" value="Crear productos">Crear productos<br>
                <input type="checkbox" name="selectedRoles[]" value="Agregar productos">Agregar productos<br>
                <input type="checkbox" name="selectedRoles[]" value="Crear hoja tecnica">Crear hoja tecnica<br>
                <input type="checkbox" name="selectedRoles[]" value="Ver hojas tecnicas">Ver hojas tecnicas<br>
                <input type="checkbox" name="selectedRoles[]" value="Ver ordenes">Ver ordenes<br>
                <input type="checkbox" name="selectedRoles[]" value="Crear ordenes">Crear ordenes<br>
                <input type="checkbox" name="selectedRoles[]" value="Ver clientes">Ver clientes<br>
                <input type="checkbox" name="selectedRoles[]" value="Crear clientes">Crear clientes<br>
                <input type="checkbox" name="selectedRoles[]" value="Ver reportes">Ver reportes<br>

                <div class="button-container">
                    <input type="submit" name="submit" value="Guardar Cambios" class="btn_save">
                </div>
            </form>

        </div>
    </section>
</body>

</html>