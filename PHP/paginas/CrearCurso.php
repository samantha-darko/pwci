<!DOCTYPE html>
<html lang="es-ES">
<?php session_start();
include_once "..\php\UsuarioLoggeado.php"
    ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://kit.fontawesome.com/854b826ed2.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../boostrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/CrearCurso.css">

    <link rel="shortcut icon" href="../multmedia/logo.png" />
    <title>Agregar Curso - Jaiko</title>
</head>

<body>

    <?php echo $menu ?>

    <div id="ventana-modal" class="modal">

    </div>

    <div class="box-container" id="box-container">
        <h1>Datos del Curso</h1>
        <form id="datos" method="post" class="datoscurso">
            <div class="bloquefoto">
                <img class="usuarioFoto" id="fotoperfil" name="fotocurso" src="" />
                <label for="">Imagen del curso:</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg" tabindex="0">
                <p id="vaciofoto">* Debe seleccionar una imagen para el curso.</p>
            </div>
            <div class="division">
                <label>Título</label>
                <input id="titulo" name="titulo" type="text" onkeypress="return Letters(event);" tabindex="1">
                <p id="cortotitulo">* El t&iacute;tulo es muy corto.</p>
                <p id="vaciotitulo">* No puede dejar el t&iacute;tulo vac&iacute;o.</p>
            </div>
            <div class="division">
                <label>Descripción</label>
                <textarea id="descripcion" name="descripcion" onkeypress="return Letters(event);"
                    tabindex="2"></textarea>
                <p id="cortodescripcion">* La descripci&oacute;n es muy corta.</p>
                <p id="vaciodescripcion">* No puede dejar la descripci&oacute;n vac&iacute;a.</p>
            </div>

            <div class="division">
                <div class="division2">
                    <div><label>Costo del curso:</label>
                        <input type="number" name="costocurso" id="costocurso" tabindex="3">
                        <p id="cerocosto">* El costo del curso no puede ser $0.</p>
                        <p id="vaciocosto">* No puede dejar el costo del curso vac&iacute;o.</p>
                    </div>

                    <div>
                        <label>Cantidad de niveles:</label>
                        <input type="number" name="cantidadlvl" id="cantidadlvl" tabindex="4">
                        <p id="cerocantidadlvl">* La cantidad de niveles no puede ser menor a 1.</p>
                        <p id="vaciocantidadlvl">* No puede dejar la cantidad de niveles vac&iacute;o.</p>
                    </div>
                </div>
            </div>

            <div class="division" id="niveles"></div>
            <button type="submit" tabindex="5">Crear Curso</button>
        </form>

    </div>

    <div class="box-footer">
        <a href="">Enseña en Jako</a>
        <a href="">¿Qui&eacute;nes somos?</a>
        <a href="">Inscribite a nuestros cursos</a>
        <a href="">Trabaja con nosotros</a>
    </div>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/CrearCurso.js"></script>

</body>

</html>