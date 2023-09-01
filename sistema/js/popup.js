function openPopup() {
    var popupContainer = document.getElementById("popupContainer");
    var overlay = document.getElementById("overlay");

    popupContainer.style.display = "block";
    overlay.style.display = "block";
}

function closePopup() {
    var popupContainer = document.getElementById("popupContainer");
    var overlay = document.getElementById("overlay");

    popupContainer.style.display = "none";
    overlay.style.display = "none";
}

function as(contentUrl) {
    var popupContent = document.getElementById("popupContent");

    var xhr = new XMLHttpRequest();
    xhr.open("GET", contentUrl, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            popupContent.innerHTML = xhr.responseText;

            executeScripts(popupContent);

            openPopup();
        }
    };
    xhr.send();
}
function loadPopupContentFromLink(contentUrl) {
    var popupContent = document.getElementById("popupContent");

    var xhr = new XMLHttpRequest();
    xhr.open("GET", contentUrl, false);  // Cambia a carga síncrona
    xhr.send();

    if (xhr.readyState === 4 && xhr.status === 200) {
        popupContent.innerHTML = xhr.responseText;

        executeScripts(popupContent);

        openPopup();
    }
}

function executeScripts(container) {
    var scripts = container.getElementsByTagName("script");
    for (var i = 0; i < scripts.length; i++) {
        var script = scripts[i];
        var newScript = document.createElement("script");
        if (script.src) {
            newScript.src = script.src;
        } else {
            newScript.innerHTML = script.innerHTML;
        }
        container.appendChild(newScript);
        script.parentNode.removeChild(script);
    }
}


function populateFieldsProductos() {
    var selectedProductId = $("#producto").val();

    $.ajax({
        type: "POST",
        url: "ajax.php", // Ruta real hacia tu archivo ajax.php
        data: { action: "getProducts", id: selectedProductId },
        dataType: "json",
        success: function (response) {
            if (response.length > 0) {
                var product = response[0];
                $("#codproducto").val(product.codproducto);
                $("#proveedor").val(product.proveedor);
                $("#medida").val(product.medida);
                $("#precio").val(product.precio);
                $("#cantidad").val(product.cantidad);
            }
        }
    });
}

function populateFieldsClientes() {
    var selectedClienteId = $("#cliente").val();

    $.ajax({
        type: "POST",
        url: "ajax.php", // Ruta real hacia tu archivo ajax.php
        data: { action: "getClientes", id_cliente: selectedClienteId },
        dataType: "json",
        success: function (response) {
            if (response.length > 0) {
                var cliente = response[0];
                $("#id_cliente").val(cliente.id_cliente);
                $("#nombre").val(cliente.nombre);
                $("#cedula").val(cliente.cedula);
                $("#telefono").val(cliente.telefono);
                $("#direccion").val(cliente.direccion);
            }
        }
    });
}


function populateFieldsUsers() {

    var selectedUsuarioId = $("#selectedUsuarioId").val();

    $.ajax({
        type: "POST",
        url: "ajax.php", // Ruta real hacia tu archivo ajax.php
        data: { action: "getUsers", idusuario: selectedUsuarioId },
        dataType: "json",
        success: function (response) {


            var usuario = response;


            $("#idusuario").val(usuario.idusuario);
            $("#usuario").val(usuario.usuario);
            $("#usuario_input").val(usuario.usuario);
            $("#nombre").val(usuario.nombre);
            $("#correo").val(usuario.correo);
            $("#cargo").val(usuario.cargo);
            $("#rol").val(usuario.rol);

        },
        error: function (error) {

        }
    });
}

function populateFieldsSupplier() {

    var selectedProveedorId = $("#selectedProveedorId").val();

    $.ajax({
        type: "POST",
        url: "ajax.php", // Ruta real hacia tu archivo ajax.php
        data: { action: "getSupplier", id_supplier: selectedProveedorId },
        dataType: "json",
        success: function (response) {

            var proveedor = response;

            $("#id_supplier ").val(proveedor.id_supplier);
            $("#cedula").val(proveedor.cedula);
            $("#proveedor").val(proveedor.proveedor);
            $("#contacto").val(proveedor.contacto);
            $("#correo").val(proveedor.correo);
            $("#telefono").val(proveedor.telefono);
            $("#direccion").val(proveedor.direccion);

        },
        error: function (error) {

        }
    });


}

$(document).ready(function () {
    $('.select2').select2();
});