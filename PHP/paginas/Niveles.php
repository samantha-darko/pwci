<!DOCTYPE html>
<html lang="es-ES">
<?php session_start();
include_once '../php/UsuarioLoggeado.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://kit.fontawesome.com/854b826ed2.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../boostrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/Niveles.css">

    <link rel="shortcut icon" href="../multmedia/logo.png" />
    <title>Acerca del Curso | Jaiko</title>
</head>

<body>

    <?php echo $menu ?>

    <div id="ventana-modal" class="modal">

    </div>

    <div class="box-container" id="box-container">

        <form id="agregar" class="agregar" enctype="multipart/form-data">
            <div class="division">
                <label>Título</label>
                <input id="titulo" name="titulo" type="text" onkeypress="return Letters(event);" tabindex="0">
                <p id="cortotitulo">* El t&iacute;tulo es muy corto.</p>
                <p id="vaciotitulo">* No puede dejar el t&iacute;tulo vac&iacute;o.</p>
            </div>

            <div class="division">
                <label>Resumen</label>
                <textarea id="resumen" name="resumen" onkeypress="return Letters(event);" tabindex="1"></textarea>
                <p id="cortoresumen">* La descripci&oacute;n es muy corta.</p>
                <p id="vacioresumen">* No puede dejar la descripci&oacute;n vac&iacute;a.</p>
            </div>

            <div class="division">
                <label for="">Contenido del nivel:</label>
                <input type="file" id="archivos" name="archivos" multiple tabindex="2">
            </div>

            <div class="division">
                <label>Costo del nivel:</label>
                <input type="number" name="costo" id="costo" placeholder="0" tabindex="3">
                <p id="cerocosto">* El costo del nivel no puede ser $0.</p>
                <p id="vaciocosto">* No puede dejar el costo del nivel vac&iacute;o.</p>
            </div>

            <button id="btnAgregar" type="submit" tabindex="4">Agregar</button>

        </form>

        <div class="niveles">
            <div class="bloquefoto">
                <img class="usuarioFoto" id="foto" name="foto" src="" />
                <label for="">Imagen del curso</label>
            </div>
            <div class="division">
                <label>Título</label>
                <h2 id="tittle" name="tittle"></h2>
            </div>
            <div class="division">
                <label>Descripción</label>
                <h3 id="description" name="description">
                    </h2>
            </div>
            <div class="division">
                <label>Precio curso</label>
                <h3 id="price" name="price">
                    </h2>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <a href="">Enseña en Jako</a>
        <a href="">¿Qui&eacute;nes somos?</a>
        <a href="">Inscribite a nuestros cursos</a>
        <a href="">Trabaja con nosotros</a>
    </div>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/Niveles.js"></script>
</body>

</html>