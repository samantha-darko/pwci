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
    <link rel="stylesheet" href="../css/Chat.css">

    <link rel="shortcut icon" href="../multmedia/logo.png" />
    <title>Chat - Jaiko</title>
</head>

<body>

    <?php echo $menu ?>

    <div id="ventana-modal" class="modal">

    </div>

    <div class="box-container" id="box-container">
        <div class="chat">
            <div class="message">
                <span class="sender">Usuario 1:</span>
                <span class="message-content">¡Hola! ¿Cómo estás?</span>
                <span class="timestamp">10:00 AM</span>
            </div>
        </div>

        <form class="message-form">
            <input type="text" id="message-input" placeholder="Escribe tu mensaje..." />
            <button type="submit">Enviar</button>
        </form>
    </div>

    <div class="box-footer">
        <a href="">Enseña en Jako</a>
        <a href="">¿Qui&eacute;nes somos?</a>
        <a href="">Inscribite a nuestros cursos</a>
        <a href="">Trabaja con nosotros</a>
    </div>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/Chat.js"></script>

</body>

</html>