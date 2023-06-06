let crear = document.querySelector("#crear");

function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    } /*else {
        let idusuario = sessionStorage.getItem('idusuario')
        $.ajax({
            url: '../php/Categorias.php?&idusuario=' + idusuario,
            success: function (resultado) {
                let res = JSON.parse(resultado);
                if (res[0] === 1) { }
            }
        })
    }*/
}

document.addEventListener("DOMContentLoaded", VerificarSesion)

$(document).ready(function () {

    crear.addEventListener("submit", function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "../php/AgregarCategoria.php",
            cache: false,
            contentType: false,
            processData: false,
            data: new FormData(this),
            success: function (resultado) {
                let res = JSON.parse(resultado);
                console.log(res)
                if (res[0] === 1) {
                    document.querySelector("#ventana-modal").style.display = "block";
                    $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-check'></i>" +
                        "<div class='aviso-modal'><p>Categoria</p> <h2>Se ha agregado la categoria correctamente</h2></div></div>");
                    setTimeout(function () {
                        $(".contenido-modal").remove();
                        document.querySelector("#ventana-modal").style.display = "none";
                        $('#crear').get(0).reset()
                        window.location.href = "Administrador.php"
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
    });

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