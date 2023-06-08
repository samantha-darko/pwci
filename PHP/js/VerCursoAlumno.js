function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }
    const valores = window.location.search;
    const urlParams = new URLSearchParams(valores);
    var curso = urlParams.get('curso');
    console.log(curso);
    $.ajax({
        url: '../php/VerCurso.php?curso=' + curso,
        success: function (resultado) {
            let res = JSON.parse(resultado);
            console.log(res)
        }
    })
}

document.addEventListener("DOMContentLoaded", VerificarSesion)

$(document).ready(function () {
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