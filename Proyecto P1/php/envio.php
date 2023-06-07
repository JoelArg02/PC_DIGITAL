<!DOCTYPE html>
<html>
<head>
  <title>Formulario PHP</title>
</head>
<body style="margin: 0; padding: 0; height: 900px" bgcolor="#F0F2F5" >
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

    // Crear el mensaje del correo
    $mensaje = "------------------------------------------------\n";
    $mensaje .= "                  FACTURA                       \n";
    $mensaje .= "------------------------------------------------\n";
    $mensaje .= "Nombres: " . $nombres . "\n";
    $mensaje .= "Apellidos: " . $apellidos . "\n";
    $mensaje .= "Cédula: " . $cedula . "\n";
    $mensaje .= "Teléfono: " . $telefono . "\n";
    $mensaje .= "Correo electrónico: " . $correo . "\n";
    $mensaje .= "------------------------------------------------\n";
    $mensaje .= "Información de Entrega:                         \n";
    $mensaje .= "------------------------------------------------\n";
    $mensaje .= "Provincia: " . $provincia . "\n";
    $mensaje .= "Ciudad: " . $ciudad . "\n";
    $mensaje .= "Dirección de domicilio: " . $direccion . "\n";
    $mensaje .= "Referencia: " . $referencia . "\n";
    $mensaje .= "------------------------------------------------\n";
    $mensaje .= "Información de Pago:                            \n";
    $mensaje .= "------------------------------------------------\n";
    $mensaje .= "Metodo de envio: " . $metodoEnvio . "\n";
    $mensaje .= "Metodo de pago: " . $metodoPago . "\n";
    $mensaje .= "------------------------------------------------\n";
    $mensaje .= "Gracias por su compra                            \n";
    $mensaje .= "------------------------------------------------\n";

    // Dirección de correo a la que se enviará el formulario
    $para = "tucorreo@example.com";

    // Asunto del correo
    $asunto = "Factura de compra en Tec Dotch";

    // Cabeceras del correo
    $cabeceras = "From: remitente@example.com" . "\r\n" .
        "Reply-To: remitente@example.com" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    // Envío del correo
    if (mail($para, $asunto, $mensaje, $cabeceras)) {
        echo "<p>El formulario se ha enviado correctamente a tu correo.</p>";
    } else {
        echo "<p>Hubo un error al enviar el formulario.</p>";
    }
  }
  ?>
  </center>
  <br>
  <center>
    <button onclick="history.go(-1);">Regresar</button>
  </center>
  
</body>
</html>
