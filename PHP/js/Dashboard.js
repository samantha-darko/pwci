let addcourse = document.querySelector("#addcourse")

function editar(idcurso) {
    sessionStorage.setItem('curso', idcurso);
    window.location.href = '../paginas/VerCursoMaestro.php?curso=' + idcurso;
}

function ver(idcurso) {
    /*$.ajax({
        url: '../php/VerCursoHistorial.php?curso=' + idcurso,
        success: function (resultado) {
            let res = JSON.parse(resultado);
            const infocurso = Object.values(res);
            sessionStorage.setItem('infocurso', infocurso);
        }
    })
    if ("infocurso" in sessionStorage) {
        let infocurso = sessionStorage.getItem('infocurso');
        console.log(infocurso)
    }*/
    window.location.href = '../paginas/VerCurso.php?curso=' + idcurso;
}

function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }
}

document.addEventListener("DOMContentLoaded", VerificarSesion)

$(document).ready(function () {

    addcourse.addEventListener("click", function (e) {
        e.preventDefault();
        window.location.href = "CrearCurso.php";
    })

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