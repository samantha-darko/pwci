<?php

include_once "Api.php";
include_once "Clases.php";

try {
    session_start();
    $api = new ApiNivel();

    $curso = $_GET["curso"];
    $usuario = $_GET["maestro"];

    $titulo = $_POST["titulo"];
    $resumen = $_POST["resumen"];
    $costo = $_POST["costo"];

    $archivos = $_FILES['archivos'];

    $datos = new Nivel(0, $curso, $titulo, $resumen, 0, $costo, 0, 0);
    $msj = $api->Agregar($datos);
    $idnivel = $msj[0];

    // Procesar los archivos y guardar en la base de datos
    foreach ($archivos['name'] as $key => $nombreArchivo) {
        $archivo = array(
            'name' => $nombreArchivo,
            'type' => $archivos['type'][$key],
            'tmp_name' => $archivos['tmp_name'][$key],
            'error' => $archivos['error'][$key],
            'size' => $archivos['size'][$key]
        );

        // Leer el contenido del archivo
        $contenido = file_get_contents($archivo['tmp_name']);

        // Procesar y guardar el archivo en la base de datos
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->prepare('INSERT INTO recursos(id_nivel_f, nombre, contenido, tipo) VALUES (:id_nivel_f, :nombre, :contenido, :tipo)');
        $stmt->bindParam(':id_nivel_f', $idnivel);
        $stmt->bindParam(':nombre', $archivo['name']);
        $stmt->bindParam(':contenido', $contenido, PDO::PARAM_LOB);
        $stmt->bindParam(':tipo', $archivo['type']);
        $stmt->execute();
    }

    echo json_encode($msj);
} catch (PDOException $e) {
    $msj = "Error en servidor: " . $e->getMessage();
    echo json_encode($msj);
}
