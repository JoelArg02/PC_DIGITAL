<?php
$alert = "";
session_start();
include "conexion.php";

if (!empty($_SESSION['active'])) {
  header('location: sistema/');
} else {
  if (!empty($_POST)) {
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
      $alert = "Ingrese su usuario y su contraseña";
    } else {
      require_once(__DIR__ . "/conexion.php");
      global $conection;

      $user = mysqli_real_escape_string($conection, $_POST['usuario']);
      $pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));
      $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$pass'");

      $result = mysqli_num_rows($query);

      if ($result > 0) {
        $data = mysqli_fetch_array($query);
        $_SESSION['active'] = true;
        
        $_SESSION['idUser'] = $data['idusuario'];
        $_SESSION['nombre'] = $data['nombre'];
        $_SESSION['email'] = $data['correo'];
        $_SESSION['user'] = $data['usuario'];
        $_SESSION['cargo'] = $data['cargo'];
        $_SESSION['rol'] = $data['rol'];

        $rol_id = $data['rol'];
        $query_permisos = "SELECT * FROM rol WHERE idrol = $rol_id";
        $result_permisos = mysqli_query($conection, $query_permisos);

        if ($result_permisos && mysqli_num_rows($result_permisos) > 0) {
          $permisos_usuario = mysqli_fetch_assoc($result_permisos);
          $_SESSION['permisos'] = $permisos_usuario;
        }

        $accion = "Inicio sesión";
        $usuario_id = $_SESSION['idUser'];
        $user_ip = $_SERVER['REMOTE_ADDR'];
        if ($user_ip == '::1' || $user_ip == '127.0.0.1') {
          $user_ip = 'local';
        }

        $query_insert = mysqli_query($conection, "INSERT INTO login_log (usuario_id, accion, user_ip) VALUES ('$usuario_id', '$accion', '$user_ip')");
        header('location: sistema/');
      } else {
        $alert = "El usuario o contraseña son incorrectos";
        session_destroy();
      }
      mysqli_close($conection);
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <link rel="shortcut icon" href="img/icono.ico">
  <link rel="stylesheet" href="css/login.css">
  <link rel="icon" type="image/jpg" href="sistema/img/favicon.png" />

</head>

<body>
  <div class="container" id="container">
    <div class="form-container sign-in-container">
      <form class="login" action="" method="post">
        <h1>Inicia sesión</h1>
        <input type="text" id="name" name="usuario" placeholder="Usuario" />
        <input type="password" id="apellido" name="clave" placeholder="Contraseña" />
        <a href="#">¿Olvidaste tu contraseña?</a>
        <input type="submit" value="INGRESAR" id="ingresar" class="button">
        <label class="alert" id="alerta">
          <?php echo isset($alert) ? $alert : ''; ?>
        </label>
      </form>
    </div>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Hola</h1>
          <p>Indicanos tus datos y te enviaremos la información</p>
          <button class="ghost" id="signIn">Sign In</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>¿Eres nuevo?</h1>
          <p>Bienvenido a nuestro sistema....</p>
          <button class="ghost" id="signUp">Contactanos</button>
        </div>
      </div>
    </div>
  </div>
  <script src="js/main.js"></script>
</body>

</html>