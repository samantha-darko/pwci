function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }
    /*if ("infocurso" in sessionStorage) {
        let resultado = sessionStorage.getItem('infocurso');
        console.log(resultado)

        if (resultado[0] != "") {
            document.querySelector("#idcurso").setAttribute("value", resultado[0])
            fotocurso.src = "data:image/png;base64," + resultado[2];
            titulo.setAttribute("value", resultado[3])
            descripcion.innerText = resultado[4]
            if (resultado[5] > "0.00") {
                preciocurso.style.display = "block"
                costocurso.setAttribute("value", resultado[5])
                tipo.innerText = 'Por curso'
                document.querySelector("#price").style.display = "block"
                document.querySelector("#price").innerText = resultado[5]
            } else {
                preciocurso.style.display = "none"
                document.querySelector("#price").innerText = "N/A"
            }
            document.querySelector("#foto").src = "data:image/png;base64," + resultado[2];
            document.querySelector("#tittle").innerText = resultado[3]
            document.querySelector("#description").innerText = resultado[4]
        } else {
            document.querySelector("#ventana-modal").style.display = "block";
            $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-xmark'></i>" +
                "<div class='aviso-modal'><p>Verifique sus datos</p> <h2>No se encontro ningun curso con ese id.</h2></div></div>");
            setTimeout(function () {
                $(".contenido-modal").remove();
                document.querySelector("#ventana-modal").style.display = "none";
                window.scrollTo({ top: 100, behavior: 'smooth' })
            }, 3000)
        }
    }*/
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