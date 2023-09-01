function validarCliente() {
    var validNombre = validarNombre();
    var validCedula = validarCedula();
    var validTelefono = validarTelefono();
    var validDireccion = validarDireccion();

    var submitButton = document.getElementById("btn_sb");
    submitButton.disabled = !(validNombre && validCedula && validTelefono && validDireccion);
}


/*VALIDACION DE NOMBRE (SOLO DOS APELLIDOS Y DOS NOMBRES)*/ 
function validarNombre() {
    var nombreInput = document.getElementById("nombre");
    var mensajeErrorNombre = document.getElementById("mensajeErrorNombre");

    var nombresApellidosRegExp = /^[A-Za-záéíóúüñÁÉÍÓÚÜÑ]+\s[A-Za-záéíóúüñÁÉÍÓÚÜÑ]+\s[A-Za-záéíóúüñÁÉÍÓÚÜÑ]+\s[A-Za-záéíóúüñÁÉÍÓÚÜÑ]+$/;

    if (!nombresApellidosRegExp.test(nombreInput.value)) {
        mensajeErrorNombre.textContent = "No cumple con el formato de nombres y apellidos";
        mensajeErrorNombre.style.color = "red";
        return false;
    } else {
        mensajeErrorNombre.textContent = "";
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

/*VALIDAR QUE EL CAMPO SEA OBLIGATORIO*/
function validarDireccion() {
    var direccionInput = document.getElementById("direccion");
    var mensajeErrorDireccion = document.getElementById("mensajeErrorDireccion");

    if (direccionInput.value.trim() === "") {
        mensajeErrorDireccion.textContent = "La dirección es obligatorio";
        mensajeErrorDireccion.style.color = "red";
        return false;
    } else {
        mensajeErrorDireccion.textContent = "";
        return true;
    }
}



