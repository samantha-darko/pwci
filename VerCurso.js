function infocurso() {
    let id = sessionStorage.getItem('curso')
    if (id.length > 0) {
        const formData = new FormData();
        formData.append("curso", id);
        fetch('../php/VerCursoMaestro.php?curso=' + id, {
            method: "GET",
            headers: { 'Content-Type': 'application/json' },
        })
            .then(res => res.json())
            .then(resultado => Mostrar(resultado))
    }
}

function readURL(input) {
    if (input.files && input.files[0]) { //Revisamos que el input tenga contenido
        var reader = new FileReader(); //Leemos el contenido

        reader.onload = function (e) { //Al cargar el contenido lo pasamos como atributo de la imagen de arriba
            fotocurso.style.display = 'block'
            vaciofoto.style.display = 'none'
            $('#fotocurso').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        fotocurso.style.display = 'none'
        vaciofoto.style.display = 'block'
    }
}

function filterFloat(evt, input) {
    // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
    var key = window.Event ? evt.which : evt.keyCode;
    var chark = String.fromCharCode(key);
    var tempValue = input.value + chark;
    if (key >= 48 && key <= 57) {
        if (filter(tempValue) === false) {
            return false;
        } else {
            return true;
        }
    } else {
        if (key == 8 || key == 13 || key == 0) {
            return true;
        } else if (key == 46) {
            if (filter(tempValue) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
function filter(__val__) {
    var preg = /^([0-9]+\.?[0-9]{0,2})$/;
    if (preg.test(__val__) === true) {
        return true;
    } else {
        return false;
    }

}

function Letters(e) {
    key = e.keyCode || e.which;
    teclado = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz123456789,¡!¿?/#";
    especiales = "8-37-38-46-164";
    teclasEspeciales = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclasEspeciales = true; break;
        }
    }
    if (letras.indexOf(teclado) == -1 && !teclasEspeciales) {
        return false;
    }
}

function Mostrar(datos) {
    if (datos['imagen'] != null) {

        var form = document.createElement('form')
        form.setAttribute('id', 'mostrar')
        document.getElementById('info').appendChild(form)

        var h1 = document.createElement('h1')
        h1.innerText = "Datos del Curso"
        document.getElementById('mostrar').appendChild(h1)

        var div = document.createElement('div')
        div.setAttribute('id', 'foto')
        div.setAttribute('class', 'bloquefoto')
        document.getElementById('mostrar').appendChild(div)

        var foto = document.createElement("img")
        foto.src = 'data:image/png;base64,' + datos['imagen']
        document.getElementById('foto').appendChild(foto)

        var label = document.createElement('h2')
        label.innerText = datos['titulo']
        document.getElementById('mostrar').appendChild(label)

        var p = document.createElement('p')
        p.innerText = datos['descripcion']
        document.getElementById('mostrar').appendChild(p)

        var btnAgregar = document.createElement('button')
        btnAgregar.setAttribute('id', 'btnVerNiveles')
        btnAgregar.setAttribute('class', 'azul')
        btnAgregar.innerText = "Ver Niveles"
        document.getElementById('mostrar').appendChild(btnAgregar)

        var divbtn = document.createElement('div')
        divbtn.setAttribute('class', 'botones')
        divbtn.setAttribute('id', 'botones')
        document.getElementById('mostrar').appendChild(divbtn)

        var btnEditar = document.createElement('button')
        btnEditar.setAttribute('id', 'btnEditar')
        btnEditar.setAttribute('class', 'verde')
        btnEditar.innerText = "Editar"
        document.getElementById('botones').appendChild(btnEditar)

        var btnEliminar = document.createElement('button')
        btnEliminar.setAttribute('id', 'btnEliminar')
        btnEliminar.setAttribute('class', 'rojo')
        btnEliminar.innerText = "Eliminar"
        document.getElementById('botones').appendChild(btnEliminar)

        if (localStorage.hasOwnProperty('id_curso'))
            localStorage.removeItem('id_curso');
        if (localStorage.hasOwnProperty('id_usuario_f'))
            localStorage.removeItem('id_usuario_f');
        if (localStorage.hasOwnProperty('titulo'))
            localStorage.removeItem('titulo');
        if (localStorage.hasOwnProperty('descripcion'))
            localStorage.removeItem('descripcion');
        if (localStorage.hasOwnProperty('activo'))
            localStorage.removeItem('activo');
        if (localStorage.hasOwnProperty('imagen'))
            localStorage.removeItem('imagen');

        sessionStorage.setItem("id_curso", datos['id_curso'])
        sessionStorage.setItem("id_usuario_f", datos['id_usuario_f'])
        sessionStorage.setItem("titulo", datos['titulo'])
        sessionStorage.setItem("descripcion", datos['descripcion'])
        sessionStorage.setItem("activo", datos['activo'])
        sessionStorage.setItem("imagen", datos['imagen'])

    }
}

function Editar() {
    document.querySelector('#mostrar').style.display = 'none'
    document.querySelector('#guardar').style.display = 'block'

    var idcurso = sessionStorage.getItem("id_curso")
    var idusuario = sessionStorage.getItem("id_usuario_f")
    var titulo = sessionStorage.getItem("titulo")
    var imagen = sessionStorage.getItem("imagen")
    var descripcion = sessionStorage.getItem("descripcion")
    var activo = sessionStorage.getItem("activo")

    document.querySelector('#editarfoto').src = 'data:image/png;base64,' + imagen
    document.querySelector('#titulo').setAttribute('value', titulo)
    document.querySelector('#descripcion').textContent = descripcion
}

document.addEventListener("DOMContentLoaded", infocurso)

$(document).ready(function () {

    $(document).on("click", "#btnEditar", function (e) {
        e.preventDefault()
        Editar()
    });

    $(document).on("click", "#btnVerNiveles", function (e) {
        e.preventDefault()
        var idcurso = sessionStorage.getItem("id_curso")
        window.location.href = '../paginas/Niveles.php?curso=' + idcurso;
    });

    document.querySelector('#btnCancelar').addEventListener('click', function (e) {
        e.preventDefault()
        document.querySelector('#mostrar').style.display = 'block'
        document.querySelector('#guardar').style.display = 'none'
    })

    document.querySelector('#btnGuardar').addEventListener('click', function (e) {
        e.preventDefault();
        efoto = false
        econtra = validarPassword(contra)
        enombre = validarNombre(nombre, vacionombre)
        eapellidop = validarNombre(apellidop, vacioapellidop)
        eapellidom = validarNombre(apellidom, vacioapellidom)
        if (document.querySelector("#fotoperfil").style.display == '') {
            efoto = true
        }

        if (econtra || enombre || eapellidop || eapellidom) {
            document.querySelector("#ventana-alertas").style.display = "block";
            $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-xmark'></i>" +
                "<div class='aviso-modal'><p>Verifique sus datos</p> <h2>No se pudieron actualizar los datos.</h2></div></div>");
            setTimeout(function () {
                $(".contenido-modal").remove();
                document.querySelector("#ventana-alertas").style.display = "none";
            }, 3000)
        } else {
            $.ajax({
                type: "POST",
                url: "../php/EditarCurso.php",
                cache: false,
                contentType: false,
                processData: false,
                data: new FormData(registro),
                success: function (resultado) {
                    let res = JSON.parse(resultado);
                    console.log(res);
                    if (res) {
                        document.querySelector("#ventana-alertas").style.display = "block";
                        $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-check'></i>" +
                            "<div class='aviso-modal'><p>Registrarse</p> <h2>Se han actualizado los datos correctamente</h2></div></div>");
                        setTimeout(function () {
                            $(".contenido-modal").remove();
                            document.querySelector("#ventana-alertas").style.display = "none";
                            $('#registro').get(0).reset()
                            window.location.href = "MiPerfil.php"
                        }, 2500)
                    } else {
                        document.querySelector("#ventana-alertas").style.display = "block";
                        $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-xmark'></i>" +
                            "<div class='aviso-modal'><p>Error en el Servidor</p> <h2>No se pudo actualizar los datos. " +
                            "Intente de nuevo más tarde.</h2></div></div>");
                        setTimeout(function () {
                            $(".contenido-modal").remove();
                            document.querySelector("#ventana-alertas").style.display = "none";
                        }, 3000)
                    }
                }

            })
        }
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
                if (res) {
                    window.location.href = '../paginas/IniciarSesion.php'
                }
            }
        })
    })
})