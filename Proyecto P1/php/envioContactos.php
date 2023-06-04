<!DOCTYPE html>
<html>
<head>
  <title>Formulario PHP</title>
</head>
<body>
  <center>
  <h1>GRACIAS POR ENVIARNOS TU COMENTARIO</h1>
  
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST["name"];
    $correo = $_POST["email"];
    $asunto = $_POST["asunto"];
	$consulta = $_POST["consulta"];
	$nPedidos = $_POST["nPedido"];

    echo "------------------------------------------------\n";
    echo "           Comentario de cliente                \n";
    echo "------------------------------------------------\n";
    echo "<p>Nombres: " . $nombres . "</p>";
    echo "<p>Correo electrónico: " . $correo . "</p>";
    echo "<p>Asunto: " . $asunto . "</p>";
    echo "<p>Motivo de consulta: " . $consulta . "</p>";
    echo "<p>N° de pedido: " . $nPedidos . "</p>";
    echo "------------------------------------------------\n";

  }
  ?>
  </center>
  <br>
  <center>
    <button onclick="history.go(-1);">Regresar</button>
  </center>
  
</body>
</html>