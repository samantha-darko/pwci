<?php
include_once "Api.php";
$items = "";

try {
    $id_usuario = $_SESSION['id_usuario'];
    $api = new ApiMaestro();
    $cursos = $api->CursosVendidos($id_usuario);
    $items .= '<div class="info">';
    $items .= '<h3>Nombre del Curso</h3>';
    $items .= '<h3>Alumnos Inscritos</h3>';
    $items .= '</div>';
    for ($i = 0; $i < count($cursos); $i++) {
        $items .= '<div class="info">';
        $items .= '<label>' . $cursos[$i]['titulo'] . '</label>';
        $items .= '<label>' . $cursos[$i]['total_alumnos'] . '</label>';
        $items .= '</div>';
    }
    $totalcursos = $api->suma_total_alumnos($id_usuario);
    $items .= '<h3 id="total">Total de cursos vendidos: ' . $totalcursos .'</h3>';
} catch (PDOException $e) {
    $msj = "Error en servidor: " . $e->getMessage();
    echo json_encode($msj);
}
