function Agregar(idcurso) {
    //window.location.href = '../php/InscribirCurso.php?curso=' + idcurso;
    idusuario = sessionStorage.getItem('idusuario')
    $.ajax({
        url: '../php/InscribirCurso.php?curso=' + idcurso + '&idusuario=' + idusuario,
        success: function (resultado) {
            let res = JSON.parse(resultado);
            if (res[0] === 1) {
                document.querySelector("#ventana-modal").style.display = "block";
                $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-check'></i>" +
                    "<div class='aviso-modal'><p>Registrarse</p> <h2>Se ha inscrito al curso de forma satisfactoria</h2></div></div>");
                setTimeout(function () {
                    $(".contenido-modal").remove();
                    document.querySelector("#ventana-modal").style.display = "none";
                    window.location.href = "PaginaPrincipal.php"
                }, 2500)
            } else {
                document.querySelector("#ventana-modal").style.display = "block";
                $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-xmark'></i>" +
                    "<div class='aviso-modal'><p>Intente de nuevo</p> <h2> " + res[1] + "</h2></div></div>");
                setTimeout(function () {
                    $(".contenido-modal").remove();
                    document.querySelector("#ventana-modal").style.display = "none";
                    window.scrollTo({ top: 100, behavior: 'smooth' })
                }, 3000)
                if (res[0] === 1062) {
                    correo.style.borderColor = '#FF331F'
                    errorcorreo.style.display = "block";
                }
            }
        }
    })
}

function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }
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
                if (res) {
                    window.location.href = '../paginas/IniciarSesion.php'
                }
            }
        })
    })
})