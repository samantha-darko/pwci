<?php

include_once "Api.php";
$items = "";

try {
    //session_start();
    $api = new ApiAlumno();

    $iduser = $_SESSION["id_usuario"];

    $ListaCursoInscrito = $api->ListaCursoInscrito($iduser);
    $actualizarvista = $api->vista_recursos_curso();

    for ($i = 0; $i < count($ListaCursoInscrito); $i++) {
        $idcurso = $ListaCursoInscrito[$i]['idcurso'];
        $items .= '<div id="' . $idcurso . '" class="cursoinscrito">';
        $items .= '<div class="infocurso">';
        $items .= '<img src="data:png;base64,' . base64_encode($ListaCursoInscrito[$i]['imagen_curso']) . '"/>';
        $items .= '<h2>' . $ListaCursoInscrito[$i]['titulo_curso'] . '</h2>';
        $items .= '<h4>' . $ListaCursoInscrito[$i]['descripcion_curso'] . '</h4>';
        $items .= '<label>Fecha de Inscripcion: ' . $ListaCursoInscrito[$i]['fecha_inscripcion'] . '</label>';
        $items .= '</div>';
        $items .= '<div class="nivel">';
        $ListaRecursos = $api->ListaRecursos($idcurso);
        for ($j = 0; $j < count($ListaRecursos); $j++) {
            $items .= '<div class="infonivel">';
            $items .= '<p>Contenido: ' . $ListaRecursos[$j]['recurso'] . '</p>';
            $items .= '<p>Archivo: <a href="data:' . $ListaRecursos[$j]['tipo'] . ';base64,' . base64_encode($ListaRecursos[$j]['contenido']) . '" download="' . $ListaRecursos[$j]['recurso'] . '">Descargar</a></p>';
            $items .= '</div>';
        }
        $items .= '</div>';
        if ($ListaCursoInscrito[$i]['finalizado'] === 0) {
            $items .= '<button onclick="finalizar(' . $idcurso . ')" id=' . $ListaCursoInscrito[$i]['fecha_inscripcion'] . '>Finalizar Curso</button>';
        } else {
            $items .= '<p>Este curso ya esta finalizado</p>';
        }
        $items .= '</div>';
    }

    //echo json_encode($msj);

} catch (PDOException $e) {
    $msj = "Error en servidor: " . $e->getMessage();
    echo json_encode($msj);
}
