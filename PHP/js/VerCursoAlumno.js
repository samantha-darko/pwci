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