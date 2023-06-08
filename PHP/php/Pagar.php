<?php
include_once "Api.php";
include_once "Clases.php";

session_start();

$status = $_POST["status"];
$total = $_POST["total"];
$idcurso = $_POST["idcurso"];
$usuario = $_POST["usuario"];

$niveles = $_POST['niveles'];
$forma = "paypal";

for ($i = 0; $i < count($niveles); $i++) {
    $nivel = $niveles[$i];
    $db = new DB();
    $conn = $db->connect();
    $stmt = $conn->prepare('call sp_inscribir_curso(0, ?, ?, 0, 0,"I");');
    $stmt->bindValue(1, $idcurso);
    $stmt->bindValue(2, $usuario);
    $stmt->bindValue(3, $nivel);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $idinsert = array($row['idcursoinscrito'], $row['codigo'], $row['mensaje']);
    }

    $db2 = new DB();
    $conn2 = $db2->connect();
    $stmt2 = $conn2->prepare('INSERT INTO pago_curso(id_curso_inscrito_f, total, forma_pago, cantidada_pago, nivel) VALUES (:id_curso_inscrito_f, :total, :forma_pago, :cantidada_pago, :nivel)');
    $stmt2->bindParam(':id_curso_inscrito_f', $idinsert[0]);
    $stmt2->bindParam(':total', $total);
    $stmt2->bindParam(':forma_pago', $forma);
    $stmt2->bindParam(':cantidada_pago', $total);
    $stmt2->bindParam(':nivel', $nivel);
    $stmt2->execute();
}

//echo json_encode($msj);


?>