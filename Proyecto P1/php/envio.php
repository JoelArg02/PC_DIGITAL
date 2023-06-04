<!DOCTYPE html>
<html>
<head>
  <title>Formulario PHP</title>
</head>
<body>
  <center>
  <h1>GRACIAS POR COMPRAR EN TEC DOTCH</h1>
  
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST["name"];
    $apellidos = $_POST["apellido"];
    $cedula = $_POST["cedula"];
    $telefono = $_POST["phone"];
    $correo = $_POST["email"];
    $provincia = $_POST["Provincia"];
    $ciudad = $_POST["Ciudad"];
    $direccion = $_POST["calle"];
    $referencia = $_POST["Referencia"];
    $metodoEnvio = $_POST["metodoEnvio"];
    $metodoPago = $_POST["metodoPago"];

    echo "------------------------------------------------\n";
    echo "                  FACTURA                       \n";
    echo "------------------------------------------------\n";
    echo "<p>Nombres: " . $nombres . "</p>";
    echo "<p>Apellidos: " . $apellidos . "</p>";
    echo "<p>Cédula: " . $cedula . "</p>";
    echo "<p>Teléfono: " . $telefono . "</p>";
    echo "<p>Correo electrónico: " . $correo . "</p>";
    echo "------------------------------------------------\n";
    echo "Información de Entrega:                         \n";
    echo "------------------------------------------------\n";
    echo "<p>Provincia: " . $provincia . "</p>";
    echo "<p>Ciudad: " . $ciudad . "</p>";
    echo "<p>Dirección de domicilio: " . $direccion . "</p>";
    echo "<p>Referencia: " . $referencia . "</p>";
    echo "------------------------------------------------\n";
    echo "Información de Pago:                            \n";
    echo "------------------------------------------------\n";
    echo "<p>Metodo de envio: " . $metodoEnvio . "</p>";
    echo "<p>Metodo de pago: " . $metodoPago . "</p>";
    echo "------------------------------------------------\n";
    echo "Gracias por su compra                            \n";
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