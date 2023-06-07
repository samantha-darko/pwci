<?php

session_start();

$status = $_POST["status"];
$total = $_POST["total"];
$idcurso = $_POST["idcurso"];

$niveles = $_POST['niveles'];
/*
for ($i = 0; $i < count($niveles); $i++) {
    $niveles(i);
    $db = new DB();
    $conn = $db->connect();
    $stmt = $conn->prepare('INSERT INTO pago_curso(id_curso_inscrito_f) VALUES (:id_nivel_f, :nombre, :contenido, :tipo)');
    $stmt->bindParam(':id_nivel_f', $idnivel);
    $stmt->bindParam(':nombre', $archivo['name']);
    $stmt->bindParam(':contenido', $contenido, PDO::PARAM_LOB);
    $stmt->bindParam(':tipo', $archivo['type']);
    $stmt->execute();
}*/

//echo json_encode($msj);


?>