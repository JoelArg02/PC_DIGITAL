function validar() {
    var validCorreo = validarCorreo();
    var validNombre = validarNombre();
    var validUsuario = validarUsuario();
    var validClave = validarClave();

    var submitButton = document.getElementById("btn_sb");
    submitButton.disabled = !(validCorreo && validNombre && validUsuario && validClave);
}

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

function validarNombre() {
    var nombreInput = document.getElementById("nombre");
    var mensajeErrorNombre = document.getElementById("mensajeErrorNombre");

    var nombreRegExp = /^^[A-Za-z]+\s[A-Za-z]+$/;

    if (!nombreRegExp.test(nombreInput.value)) {
        mensajeErrorNombre.textContent = "Ingresa un nombre y un apellido";
        mensajeErrorNombre.style.color = "red";
        return false;
    } else {
        mensajeErrorNombre.textContent = "";
        return true;
    }
}

function validarUsuario() {
    var usuarioInput = document.getElementById("usuario");
    var mensajeErrorUsuario = document.getElementById("mensajeErrorUsuario");

    if (usuarioInput.value.trim() === "") {
        mensajeErrorUsuario.textContent = "El usuario es obligatorio";
        mensajeErrorUsuario.style.color = "red";
        return false;
    } else {
        mensajeErrorUsuario.textContent = "";
        return true;
    }
}

function validarClave() {
    var claveInput = document.getElementById("clave");
    var mensajeErrorClave = document.getElementById("mensajeErrorClave");

    var claveRegExp = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,16}$/;

    if (!claveRegExp.test(claveInput.value)) {
        mensajeErrorClave.textContent = "La contraseña debe tener entre 8 y 16 caracteres, al menos una letra mayúscula, una minúscula, un número y un caracter especial";
        mensajeErrorClave.style.color = "red";
        return false;
    } else {
        mensajeErrorClave.textContent = "";
        return true;
    }
}
validar();