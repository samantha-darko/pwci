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

    document.querySelector('#btnCancelar').addEventListener('click', function (e) {
        e.preventDefault()
        document.querySelector('#mostrar').style.display = 'block'
        document.querySelector('#guardar').style.display = 'none'
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
            success: function (resultado) {
                var res = JSON.parse(resultado)
                if (res) {
                    window.location.href = '../paginas/IniciarSesion.php'
                }
            }
        })
    })
})