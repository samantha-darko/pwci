<?php

include_once "Api.php";
include_once "Clases.php";

try {
    session_start();
    $api = new ApiCliente();

    $curso = $_GET["curso"];
    $idusuario = $_GET["idusuario"];

    $msj = $api->InscribirCurso($curso,$idusuario);

    echo json_encode($msj);

} catch (PDOException $e) {
    $msj = "Error en servidor: " . $e->getMessage();
    echo json_encode($msj);
}

?>