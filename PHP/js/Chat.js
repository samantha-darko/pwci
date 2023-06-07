function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }else{
        iduser = sessionStorage.getItem("idusuario")
        document.querySelector("#idEnviado").value = iduser
        document.querySelector("#idEnviado").disabled = "true";
    }
}

document.addEventListener("DOMContentLoaded", VerificarSesion)

$(document).ready(function () {

    function enviarMensaje() {
        var idEnviado = $("#idEnviado").val();
        var idRecibido = $("#idRecibido").val();
        var mensaje = $("#mensaje").val();

        $.ajax({
            type: "POST",
            url: "../php/enviarMensaje.php", // El archivo PHP que procesará la solicitud
            data: {
                idEnviado: idEnviado,
                idRecibido: idRecibido,
                mensaje: mensaje
            },
            success: function (response) {
                alert("Mensaje enviado correctamente");
                // Limpiar el formulario después de enviar el mensaje
                $("#idEnviado").val("");
                $("#idRecibido").val("");
                $("#mensaje").val("");
            },
            error: function (xhr, status, error) {
                alert("Error al enviar el mensaje: " + error);
            }
        });
    }

    // Función para recibir mensajes
    function recibirMensajes() {
        var idUsuario = sessionStorage.getItem("idusuario")

        $.ajax({
            type: "GET",
            url: "../php/recibirMensajes.php", // El archivo PHP que procesará la solicitud
            data: {
                idUsuario: idUsuario
            },
            success: function (response) {
                console.log(response)
                $("#mensajesContainer").html(response);
            },
            error: function (xhr, status, error) {
                alert("Error al recibir los mensajes: " + error);
            }
        });
    }

    // Manejar el envío del formulario para enviar mensajes
    $("#enviarMensajeForm").submit(function (event) {
        event.preventDefault(); // Evitar el envío del formulario

        enviarMensaje();
    });

    // Ejecutar la función para recibir mensajes cuando se cargue la página
    recibirMensajes();

    document.querySelector('#salir').addEventListener('click', function (e) {
        e.preventDefault();
        if ('idusuario' in sessionStorage) {
            sessionStorage.removeItem('idusuario');
        }
        if ('rol' in sessionStorage) {
            sessionStorage.removeItem('rol');
        }
        sessionStorage.clear()
        $.ajax({
            url: '../php/CerrarSesion.php',
            success: function (resultado) {
                var res = JSON.parse(resultado)
                console.log(res)
                if (res) {
                    window.location.href = '../paginas/IniciarSesion.php'
                }
            }
        })
    })

})