setTimeout(function () {
    var popupMessage = document.getElementById("popupMessage");
    if (popupMessage) {
        popupMessage.style.display = "none";
    }
}, 4000);

function calcularPrecioIVA() {
    var precio = parseFloat(document.getElementById("precio").value);
    var precioIVA = precio * 1.12;

    document.getElementById("precio_iva").value = precioIVA.toFixed(2);
}

function calcularPrecioSinIVA() {
    var precioIVA = parseFloat(document.getElementById("precio_iva").value);
    var precio = precioIVA / 1.12;

    document.getElementById("precio").value = precio.toFixed(2);
}