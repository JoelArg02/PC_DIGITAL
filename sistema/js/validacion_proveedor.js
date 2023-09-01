function validar() {
    var validProveedor = validarProveedor();
    var validCedula = validarCedula();
    var validNombre = validarNombre();
    var validTelefono = validarTelefono();
    var validCorreo = validarCorreo();
    var validDireccion = validarDireccion();

    var submitButton = document.getElementById("btn_sb");
    submitButton.disabled = !(validProveedor && validCedula && validNombre && validTelefono && validCorreo && validDireccion);
}

/*VALIDAR PROVEEDOR OBLIGATORIO*/
function validarProveedor() {
    var proveedorInput = document.getElementById("proveedor");
    var mensajeErrorProveedor = document.getElementById("mensajeErrorProveedor");

    if (proveedorInput.value.trim() === "") {
        mensajeErrorProveedor.textContent = "El nombre del proveedor es obligatorio";
        mensajeErrorProveedor.style.color = "red";
        return false;
    } else {
        mensajeErrorProveedor.textContent = "";
        return true;
    }
}


/*VALIDACION DE  CEDULA (SOLO CEDULAS ECUATORIANAS)*/
function validarCedula() {
    var cedulaInput = document.getElementById("cedula");
    var mensajeErrorCedula = document.getElementById("mensajeErrorCedula");

    if (!validarCedulaEcuatoriana(cedulaInput.value)) {
        mensajeErrorCedula.textContent = "Ingrese una cédula ecuatorina válida";
        mensajeErrorCedula.style.color = "red";
        return false;
    } else {
        mensajeErrorCedula.textContent = "";
        return true;
    }
}

function validarCedulaEcuatoriana(cedula) {

    if (cedula.length !== 10 || isNaN(cedula)) {
        return false;
    }

    var digitos = cedula.substr(0, 9);
    var suma = 0;
    for (var i = 0; i < digitos.length; i++) {
        var digito = parseInt(digitos[i]);
        if (i % 2 === 0) {
            digito *= 2;
            if (digito > 9) {
                digito -= 9;
            }
        }
        suma += digito;
    }

    var verificador = 10 - (suma % 10);
    if (verificador === 10) {
        verificador = 0;
    }

    var ultimoDigito = parseInt(cedula[9]);
    return verificador === ultimoDigito;
}

/*VALIDAR NOMBRE */
function validarNombre() {
    var nombreInput = document.getElementById("contacto");
    var mensajeErrorNombre = document.getElementById("mensajeErrorNombre");

    if (!/^[A-Za-z\s]+$/.test(nombreInput.value)) {
        mensajeErrorNombre.textContent = "Ingresa un nombre y apellido";
        mensajeErrorNombre.style.color = "red";
        return false;

    } else {
        mensajeErrorNombre.textContent = "";
        return true;

    }
}

/*VALIDACION DE TELEFONO ECUATORIANO (SOLO fORMATO ECUATORIANO)*/

function validarTelefono() {
    var telefonoInput = document.getElementById("telefono");
    var mensajeErrorTelefono = document.getElementById("mensajeErrorTelefono");

    if (!validarNumeroCelular(telefonoInput.value)) {
        mensajeErrorTelefono.textContent = "Ingresa un número de celular ecuatoriano válido";
        mensajeErrorTelefono.style.color = "red";
        return false;
    } else {
        mensajeErrorTelefono.textContent = "";
        return true;
    }
}

function validarNumeroCelular(numero) {
    var celularRegExp = /^09\d{8}$/;

    return celularRegExp.test(numero);
}


/*VALIDAR CORREO VALIDO*/
function validarCorreo() {
    var correoInput = document.getElementById("correo");
    var mensajeErrorCorreo = document.getElementById("mensajeErrorCorreo");

    if (!/^[a-zA-Z0-9!#$%&'*\/=?^_`{|}~+\-]+@[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?)*$/.test(correoInput.value)) {
        mensajeErrorCorreo.textContent = "El correo debe tener el formato: example@dominio.com";
        mensajeErrorCorreo.style.color = "red";
        return false;
    } else {
        mensajeErrorCorreo.textContent = "";
        return true;

    }
    
}

/*VALIDAR DIRECCION*/
function validarDireccion() {
    var direccionInput = document.getElementById("direccion");
    var mensajeErrorDireccion = document.getElementById("mensajeErrorDireccion");

    if (direccionInput.value.trim() === "") {
        mensajeErrorDireccion.textContent = "La dirección es obligatoria";
        mensajeErrorDireccion.style.color = "red";
        return false;

    } else {
        mensajeErrorDireccion.textContent = "";
        return true;

    }
}

validar();