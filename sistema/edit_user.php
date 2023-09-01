<?php
session_start();
include "../conexion.php";
if (!isset($_SESSION['permisos']['permiso_crear_usuarios']) || $_SESSION['permisos']['permiso_crear_usuarios'] != 1) {
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
            <h1>Editar usuario</h1>

            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <span class="close-button" onclick="closePopup()">&times;</span>

            <form id="entryForm" action="process/process_edit_user.php" method="post">
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

                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" placeholder="usuario" pattern="^[a-z]+$"
                    onchange="validar()" required>
                <div id="mensajeErrorUsuario" class="mensaje-error"></div>



                <label for="nombre">Nombre y Apellido </label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre Apellido"
                    title="El formato debe ser 'Nombre Apellido'" onchange="validar()" required>
                <div id="mensajeErrorNombre" class="mensaje-error"></div>

                <label for="correo">Correo electrónico: </label>
                <input type="email" name="correo" id="correo" placeholder="texto@dominio.com" onchange="validar()"
                    required>
                <div id="mensajeErrorCorreo" class="mensaje-error"></div>

                <label for="cargo">Cargo: </label>
                <select name="cargo" id="cargo" title="Selecciona un cargo" required>
                    <option value="" disabled selected>Selecciona un cargo</option>
                    <?php if (isset($_SESSION['idUser']) && $_SESSION['idUser'] == 1) { ?>
                        <option value="Superadmin">Superadmin</option>
                    <?php } ?>
                    <option value="Gerente">Gerente</option>
                    <option value="Vendedor">Vendedor</option>
                    <option value="Almacenero">Almacenero</option>
                    <option value="Contador">Contador</option>
                    <option value="Vendedor>">Vendedor</option>
                </select>

                <label for="clave">Clave: </label>
                <input type="password" name="clave" id="clave" placeholder="Clave de acceso" onchange="validar()"
                    title="La clave debe tener al menos 8 caracteres, incluir una mayúscula y un carácter especial">
                <div id="mensajeErrorClave" class="mensaje-error"></div>

                <label for="estatus">Activar/Desactivar:</label>
                <input type="checkbox" name="estatus" id="estatus" checked>

                <div class="button-container">
                    <input type="submit" name="submit" value="Guardar Cambios" id="btn_sb" class="btn_save">
                </div>
            </form>

        </div>
    </section>
</body>

</html>