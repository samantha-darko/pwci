<?php
include_once "Api.php";
$items = "";

try {
    $idcursoinscrito = $_SESSION['id_usuario'];
    $api = new ApiAlumno();
    $cursos = $api->Kardex($idcursoinscrito);
    $items .= '<div class="info">';
    $items .= '<h3>Nombre del Curso</label>';
    $items .= '<h3>Fecha de Inscripción</label>';
    $items .= '<h3>Finalizado</label>';
    $items .= '</div>';
    for ($i = 0; $i < count($cursos); $i++) {
        $items .= '<div class="info">';
        $items .= '<label><a href="VerCursoAlumno.php?curso='. $cursos[$i]['id_curso'] .'">' . $cursos[$i]['titulo'] . '</a></label>';
        $items .= '<label>' . $cursos[$i]['fecha_inscripcion'] . '</label>';
        if ($cursos[$i]['finalizado'] === 1)
            $items .= '<label>Si</label>';
        else
            $items .= '<label>No</label>';
        $items .= '</div>';
    }
} catch (PDOException $e) {
    $msj = "Error en servidor: " . $e->getMessage();
    echo json_encode($msj);
}
