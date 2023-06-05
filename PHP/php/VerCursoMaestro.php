<?php

include_once 'Api.php';
include_once 'Clases.php';

try {
    session_start();
    $msj = [];
    $api = new ApiCurso();
    $curso = $_GET["curso"];
    $usuario = $_SESSION["id_usuario"];
    $msj = $api->Ver($curso, $usuario);
    
    echo json_encode($msj);
} catch (PDOException $e) {
    $msj = "Error en servidor: " . $e->getMessage();
    echo json_encode($msj);
}

?>