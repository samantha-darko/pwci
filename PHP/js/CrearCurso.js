let datos = document.querySelector("#datos");

let titulo = document.querySelector("#titulo");
let cortotitulo = document.querySelector("#cortotitulo");
let vaciotitulo = document.querySelector("#vaciotitulo");

let descripcion = document.querySelector("#descripcion");
let cortodescripcion = document.querySelector("#cortodescripcion");
let vaciodescripcion = document.querySelector("#vaciodescripcion");

let fotoperfil = document.querySelector("#fotoperfil");
let vaciofoto = document.querySelector("#vaciofoto");

let costocurso = document.querySelector("#costocurso");
let cerocosto = document.querySelector("#cerocosto");
let vaciocosto = document.querySelector("#vaciocosto");

let cantidadlvl = document.querySelector("#cantidadlvl");
let cerocantidadlvl = document.querySelector("#cerocantidadlvl");
let vaciocantidadlvl = document.querySelector("#vaciocantidadlvl");

function Agregar(datos) {

    if (datos[0] === 1) {
        document.getElementById("ventana-modal").style.display = "block"
        $(".modal").append("<div class='contenido-modal'> <a href='login.php'><img src='../multmedia/logo.png' titlle='Inicio'></a>" +
            "<div class='aviso-modal'> <p>Agregar Curso </p> <h2>Se ha agregado el curso de forma correcta.</h2> </div> </div>")
        setTimeout(function () {
            $(".contenido-modal").remove();
            window.location.href = "CrearCurso.php";
        }, 3000)
    } else {
        document.getElementById("ventana-modal").style.display = "block"
        $(".modal").append("<div class='contenido-modal'> <a href='login.php'><i class='fa-sharp fa-solid fa-circle-xmark'></i><div class='aviso-modal'></a>" +
            "<div class='aviso-modal'> <p>No se creo el curso</p> <h2>" + datos[1] + "</h2> </div> </div>");
        setTimeout(function () {
            $(".contenido-modal").remove();
            document.getElementById("ventana-modal").style.display = "none"
        }, 3000)
    }
}

function readURL(input) {
    if (input.files && input.files[0]) { //Revisamos que el input tenga contenido
        var reader = new FileReader(); //Leemos el contenido

        reader.onload = function (e) { //Al cargar el contenido lo pasamos como atributo de la imagen de arriba
            fotoperfil.style.display = 'block'
            vaciofoto.style.display = 'none'
            $('#fotoperfil').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        fotoperfil.style.display = 'none'
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

function Enfocar(objeto) {
    objeto.style.borderColor = 'blue'
    objeto.style.boxShadow = '0 1px 1px rgba(12, 22, 51, 0.075)inset, 0 0 8px rgba(7, 145, 230, 0.6)'
}

function Desenfocar(objeto, objeto2) {
    objeto.style.boxShadow = 'none'
    if (objeto.value.length > 0) {
        objeto.style.borderColor = 'gray'
        objeto2.style.display = 'none'
    } else {
        objeto.style.borderColor = '#FF331F'
        objeto2.style.display = 'block'
    }
}

$(document).ready(function () {
    if ('cantidadlvl' in sessionStorage) {
        sessionStorage.removeItem('cantidadlvl');
    }

    $("#image").change(function () {
        readURL(this);
    });

    datos.addEventListener("submit", function (e) {
        e.preventDefault();
        if (titulo.value.length > 4 && descripcion.value.length > 9 && costocurso.value.length > 0 && cantidadlvl.value.length > 0 && fotoperfil.style.display != '') {
            $.ajax({
                type: "POST",
                url: "../php/AgregarCurso.php",
                cache: false,
                contentType: false,
                processData: false,
                data: new FormData(this),
                success: function (resultado) {
                    let res = JSON.parse(resultado);
                    Agregar(res);
                }
            })
        } else {
            if (titulo.value.length == 0) {
                titulo.style.borderColor = '#FF331F'
                vaciotitulo.style.display = 'block'
            } else if (titulo.value.length < 5) {
                titulo.style.borderColor = '#FF331F'
                cortotitulo.style.display = 'block'
            }

            if (descripcion.value.length == 0) {
                descripcion.style.borderColor = '#FF331F'
                vaciodescripcion.style.display = 'block'
            } else if (descripcion.value.length < 10) {
                descripcion.style.borderColor = '#FF331F'
                cortodescripcion.style.display = 'block'
            }

            if (costocurso.value.length == 0) {
                costocurso.style.borderColor = '#FF331F'
                vaciocosto.style.display = 'block'
            } else if (costocurso.value == 0) {
                costocurso.style.borderColor = '#FF331F'
                cerocosto.style.display = 'block'
            }

            if (cantidadlvl.value.length == 0) {
                cantidadlvl.style.borderColor = '#FF331F'
                vaciocantidadlvl.style.display = 'block'
            } else if (cantidadlvl.value == 0) {
                cantidadlvl.style.borderColor = '#FF331F'
                cerocantidadlvl.style.display = 'block'
            }

            if (fotoperfil.style.display == '') {
                vaciofoto.style.display = 'block'
            }
            document.querySelector("#ventana-modal").style.display = "block";
            $(".modal").append("<div class='contenido-modal'><i class='fa-sharp fa-solid fa-circle-xmark'></i>" +
                "<div class='aviso-modal'><p>Verifique sus datos</p> <h2>No se pudo realizar el registro</h2></div></div>");
            setTimeout(function () {
                $(".contenido-modal").remove();
                document.querySelector("#ventana-modal").style.display = "none";
                window.scrollTo({ top: 100, behavior: 'smooth' })
            }, 3000)
        }

    });

    titulo.addEventListener("focus", function (e) {
        e.preventDefault();
        Enfocar(titulo)
    })
    titulo.addEventListener("blur", function (e) {
        e.preventDefault();
        Desenfocar(titulo, vaciotitulo)
    })

    descripcion.addEventListener("focus", function (e) {
        e.preventDefault();
        Enfocar(descripcion)
    })
    descripcion.addEventListener("blur", function (e) {
        e.preventDefault();
        Desenfocar(descripcion, vaciodescripcion)
    })

    costocurso.addEventListener("focus", function (e) {
        e.preventDefault();
        Enfocar(costocurso)
    })
    costocurso.addEventListener("blur", function (e) {
        e.preventDefault();
        Desenfocar(costocurso, vaciocosto)
        costocurso.style.boxShadow = 'none'
        cerocosto.style.display = 'none'
        if (costocurso.value.length > 0) {
            if (costocurso.value > 0) {
                costocurso.style.borderColor = 'gray'
                cerocosto.style.display = 'none'
            } else {
                costocurso.style.borderColor = '#FF331F'
                cerocosto.style.display = 'block'
            }
        }

    })

    cantidadlvl.addEventListener("focus", function (e) {
        e.preventDefault();
        Enfocar(cantidadlvl)
    })
    cantidadlvl.addEventListener("blur", function (e) {
        e.preventDefault();
        Desenfocar(cantidadlvl, vaciocantidadlvl)
        cantidadlvl.style.boxShadow = 'none'
        cerocantidadlvl.style.display = 'none'
        if (cantidadlvl.value.length > 0) {
            if (cantidadlvl.value > 0) {
                cantidadlvl.style.borderColor = 'gray'
                cerocantidadlvl.style.display = 'none'
            } else {
                cantidadlvl.style.borderColor = '#FF331F'
                cerocantidadlvl.style.display = 'block'
            }
        }

    })

    cantidadlvl.addEventListener("change", function (e) {
        e.preventDefault()
        if ('cantidadlvl' in sessionStorage) {
            let num = parseInt(sessionStorage.getItem('cantidadlvl'))
            for (let i = 0; i < num; i++) {
                document.getElementById(('div' + i)).remove()
            }
            sessionStorage.removeItem('cantidadlvl');
        }
        niveles = cantidadlvl.value
        if (niveles > 0) {
            for (let i = 0; i < niveles; i++) {
                var div = document.createElement('div')
                div.setAttribute('id', 'div' + i)
                document.getElementById('niveles').appendChild(div)

                var label = document.createElement('label')
                label.innerText = "Titulo del nivel " + (i + 1) + ":"
                document.getElementById('div' + i).appendChild(label)

                var input = document.createElement('input')
                input.setAttribute('id', 'txtinput' + i)
                document.getElementById('div' + i).appendChild(input)

                var pcorto = document.createElement('p')
                pcorto.setAttribute('id', 'pcorto' + i)
                pcorto.innerText = "* El título es muy corto."
                document.getElementById('div' + i).appendChild(pcorto)
                var pvacio = document.createElement('p')
                pvacio.setAttribute('id', 'pvacio' + i)
                pvacio.innerText = "* No puede dejar el título vac&iacute;o."
                document.getElementById('div' + i).appendChild(pvacio)

                var label = document.createElement('label')
                label.innerText = "Descripción del nivel " + (i + 1) + ":"
                document.getElementById('div' + i).appendChild(label)

                var txtarea = document.createElement('textarea')
                txtarea.setAttribute('id', 'txtarea' + i)
                document.getElementById('div' + i).appendChild(txtarea)

                var pcortotxt = document.createElement('p')
                pcortotxt.setAttribute('id', 'pcortotxt' + i)
                pcortotxt.innerText = "* La descripción es muy corta."
                document.getElementById('div' + i).appendChild(pcortotxt)
                var pvaciotxt = document.createElement('p')
                pvaciotxt.setAttribute('id', 'pvaciotxt' + i)
                pvaciotxt.innerText = "* No puede dejar la descripción vac&iacute;o."
                document.getElementById('div' + i).appendChild(pvaciotxt)
            }
            sessionStorage.setItem('cantidadlvl', niveles)
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
        $.ajax({
            url: '../php/CerrarSesion.php',
            success: window.location.href = '../paginas/IniciarSesion.php'
        })
    })
});