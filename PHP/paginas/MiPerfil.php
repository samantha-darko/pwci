<!DOCTYPE html>
<html lang="es">
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
    <link rel="stylesheet" href="../css/MiPerfil.css">

    <link rel="shortcut icon" href="../multmedia/logo.png" />
    <title>Mi Perfil | Jaiko</title>
</head>

<body onload="VerificarSesion();">

    <?php echo $menu ?>
    <!--<div class="box-menu">
        <nav id="menu-left">
            <ul>
                <li><a href="PaginaPrincipal.php"><img src="../multmedia/logo.png" tittle="Jaiko">Jaiko</a>

                    <ul>
                        <li><a href="construccion.php"><i class="fa fa-solid fa-circle-info"></i></i> Contactanos</a>
                        </li>
                        <li><a href="construccion.php"><i class="fa fa-solid fa-map-location-dot"></i></i> Ver
                                Ubicación</a></li>
                        <li><a href="construccion.php"><i class="fa fa-solid fa-address-card"></i></i> Acerca de
                                Nosotros</a></li>
                    </ul>

                </li>
            </ul>
        </nav>

        <nav id="menu-right">
            <ul>
                <li>
                    <a href="">
                        <?php //echo '<img src="data:png;base64,' . base64_encode($datos->imagen) . '"/>'; ?>
                        <?php //echo '<label>' . $datos->nombre . '</label>'; ?>
                    </a>
                    <ul>
                        <li><a href="MiPerfil.php"><i class="fa fa-duotone fa-eye"></i> Mi Perfil</a></li>
                        <li><a href="MisCursos.php"><i class="fa-solid fa-circle-plus"></i> Mis Cursos</a></li>
                        <li><a href="../php/CerrarSesion.php">
                                <i class="fa fa-sharp fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="buscador">
            <input type="search" name="" id="nav-search" placeholder="Buscador...">
            <button id="btn-search"><i class="fa fa-solid fa-magnifying-glass"></i></button>
        </div>
    </div>-->

    <div id="ventana-alertas" class="modal"></div>

    <div class="box-container" id="box-container">
        <div class="ver">

            <div class="encabezado">
                <h1>Mi Perfil</h1>
            </div>
            <div class="datos">
                <div class="group">
                    <?php echo '<div><img class="foto" id="foto" name="foto" src="data:png;base64,' . base64_encode($datos->imagen) . '"/></div>'; ?>
                </div>
                <div class="group">
                    <label>Correo:</label>
                    <?php echo '<p>' . $datos->email . '</p>'; ?>

                    <label>Nombre:</label>
                    <?php echo '<p>' . $datos->nombre . ' ' . $datos->apellido_p . ' ' . $datos->apellido_m . '</p>'; ?>

                    <label>Fecha de Nacimiento:</label>
                    <?php echo '<p>' . $datos->fch_nacimiento . '</p>'; ?>

                    <label>Rol:</label>
                    <?php echo '<p>' . $datos->rol . '</p>'; ?>

                    <label>G&eacute;nero:</label>
                    <?php echo '<p>' . $datos->genero . '</p>'; ?>

                    <label>Fecha de Ingreso:</label>
                    <?php echo '<p>' . $datos->fch_ingreso . '</p>'; ?>
                </div>
            </div>
            <div class="datos">
                <button id="editar" onclick="location.href = '../paginas/EditarPerfil.php';">Editar Perfil</button>
                <button id="cerrar" onclick="location.href = '../php/CerrarSesion.php';">Cerrar Sesi&oacute;n</button>
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
    <script src="../js/mi-perfil.js"></script>

</body>

</html>