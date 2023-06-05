function CargarInfo() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }

    let idcurso = sessionStorage.getItem("curso")
    $.ajax({
        url: '../php/ObtenerNiveles.php',
        success: function (resultado) {
            var res = JSON.parse(resultado)
            if (res) {
                
            }
        }
    })
}

document.addEventListener("DOMContentLoaded", CargarInfo)

$(document).ready(function () {

})