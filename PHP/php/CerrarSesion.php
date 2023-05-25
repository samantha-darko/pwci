<?php

try {
    session_start();

    if (isset($_SESSION['acceso'])) {
        unset($_SESSION['acceso']);
    }
    if (isset($_SESSION['sessCustomerID'])) {
        unset($_SESSION['sessCustomerID']);
    }
    if (isset($_SESSION['id_usuario'])) {
        unset($_SESSION['id_usuario']);
    }
    if (isset($_SESSION['email'])) {
        unset($_SESSION['email']);
    }
    if (isset($_SESSION['contra'])) {
        unset($_SESSION['contra']);
    }
    if (isset($_SESSION['rol'])) {
        unset($_SESSION['rol']);
    }
    if (isset($_SESSION['imagen'])) {
        unset($_SESSION['imagen']);
    }
    if (isset($_SESSION['nombre'])) {
        unset($_SESSION['nombre']);
    }
    if (isset($_SESSION['apellido_p'])) {
        unset($_SESSION['apellido_p']);
    }
    if (isset($_SESSION['apellido_m'])) {
        unset($_SESSION['apellido_m']);
    }
    if (isset($_SESSION['fch_nacimiento'])) {
        unset($_SESSION['fch_nacimiento']);
    }
    if (isset($_SESSION['genero'])) {
        unset($_SESSION['genero']);
    }
    echo json_encode(array(true));
    session_write_close();
} catch (Throwable $th) {
    echo json_encode($th);
}
/*$script = '<script> document.querySelector("#ventana-alertas").style.display = "block"';
$script .= '$(".modal").append("<div class="contenido-modal"><i class="fa-sharp fa-solid fa-circle-check"></i>"';
$script .= '"<div class="aviso-modal"><p>Cerrar Sesión</p> <h2>Espere un momento mientras se cierra la sesión.</h2></div></div>");';
$script .= 'setTimeout(function () {';
$script .= '$(".contenido-modal").remove();';
$script .= 'document.querySelector("#ventana-alertas").style.display = "none";';
$script .= 'window.location.href = "../paginas/IniciarSesion.php"';
$script .= '}, 2500)</script>';*/
//echo $script;
?>