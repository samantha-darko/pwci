<!DOCTYPE html>
<html lang="es-ES">
<?php session_start();
include_once '../php/UsuarioLoggeado.php'; 
include_once '../php/Cursos.php'; 
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://kit.fontawesome.com/854b826ed2.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../boostrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/PaginaPrincipal.css">

    <link rel="shortcut icon" href="../multmedia/logo.png" />
    <title>¡Bienvenida/o! - Jaiko</title>
</head>

<body>

    <?php echo $menu; ?>

    <div id="ventana-modal" class="modal">

    </div>

    <div class="box-container" id="box-container">

        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="../multmedia/curso1.webp" class="d-block w-100" alt="...">
                    <!--<div class="carousel-caption d-none d-md-block">
                        <h5>Etiqueta de la primera diapositiva</h5>
                        <p>Algún contenido placeholder representativo para la primera diapositiva.</p>
                    </div>-->
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="../multmedia/curso2.jpg" class="d-block w-100" alt="...">
                    <!--<div class="carousel-caption d-none d-md-block">
                        <h5>Etiqueta de la segunda diapositiva</h5>
                        <p>Algún contenido placeholder representativo para la segunda diapositiva.</p>
                    </div>-->
                </div>
                <div class="carousel-item">
                    <img src="../multmedia/curso1.webp" class="d-block w-100" alt="...">
                    <!--<div class="carousel-caption d-none d-md-block">
                        <h5>Etiqueta de la tercera diapositiva</h5>
                        <p>Algún contenido placeholder representativo para la tercera diapositiva.</p>
                    </div>-->
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <?php
        $items = Paginar(3);
        if ($items != '') {
            echo $items;
        } ?>

    </div>

    <div class="box-footer">
        <a href="">Enseña en Jako</a>
        <a href="">¿Qui&eacute;nes somos?</a>
        <a href="">Inscribite a nuestros cursos</a>
        <a href="">Trabaja con nosotros</a>
    </div>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/PaginaPrincipal.js"></script>

</body>

</html>