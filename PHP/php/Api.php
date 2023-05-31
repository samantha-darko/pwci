<?php

include_once("conexion.php");
include_once("Clases.php");

class ApiCliente
{
    public function Agregar($datos)
    {
        try {
            $db = new DB();
            $conn = $db->connect();

            $sql = ("call sp_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, 'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->email);
            $stmt->bindValue(2, $datos->contra);
            $stmt->bindValue(3, $datos->rol);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->nombre);
            $stmt->bindValue(6, $datos->apellido_p);
            $stmt->bindValue(7, $datos->apellido_m);
            $stmt->bindValue(8, $datos->fch_nacimiento);
            $stmt->bindValue(9, $datos->genero);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            } else
                $msj = false;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function Editar($datos)
    {
        try {
            $msj = '';
            $db = new DB();
            $conn = $db->connect();

            $sql = ("call sp_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, 'U');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->email);
            $stmt->bindValue(2, $datos->contra);
            $stmt->bindValue(3, $datos->rol);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->nombre);
            $stmt->bindValue(6, $datos->apellido_p);
            $stmt->bindValue(7, $datos->apellido_m);
            $stmt->bindValue(8, $datos->fch_nacimiento);
            $stmt->bindValue(9, $datos->genero);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();

                if (!empty($row['codigo'])) {
                    $msj = $row['codigo'];
                }
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function IniciarSesion($user, $pass)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            $sql = '';
            $sql = ("call sp_usuario_inicio_sesion(?, ?);");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $user);
            $stmt->bindValue(2, $pass);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();

                if (!empty($row['mensaje'])) {
                    return $row['mensaje'];
                }

                $item = array(
                    "id_usuario" => $row['id_usuario'],
                    "email" => $row['email'],
                    "contra" => $row['contra'],
                    "rol" => $row['rol'],
                    "imagen" => $row['imagen'],
                    "nombre" => $row['nombre'],
                    "apellido_p" => $row['apellido_p'],
                    "apellido_m" => $row['apellido_m'],
                    "fch_nacimiento" => $row['fch_nacimiento'],
                    "genero" => $row['genero'],
                    "errores" => $row['errores'],
                    "baja_logica" => $row['baja_logica'],
                    "fch_ingreso" => $row['fch_ingreso']
                );
                return $item;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function InscribirCurso($idcurso, $idusuario)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            $sql = '';
            $sql = ("call sp_inscribir_curso(0,?,?,0,0,'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $idcurso);
            $stmt->bindValue(2, $idusuario);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();

                if (!empty($row['codigo'])) {
                    $msj = array(
                        $row['codigo'],
                        $row['mensaje']
                    );
                }
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function MisCursos($idusuario){
        $db = new DB();
        $conn = $db->connect();
        $sql = ("SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso_inscrito");
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

        return $result;
    }

    function Listado($inicio, $registros)
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = ("SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso_inscrito LIMIT $inicio,$registros");
        $stmt = $conn->prepare($sql);

        return $stmt;
    }
}

class ApiCurso
{
    public function Agregar($datos)
    {
        try {
            $msj = false;
            $db = new DB();
            $conn = $db->connect();

            $sql = ("call sp_curso(0, ?, ?, ?, ?, ?, 'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->id_usuario_f);
            $stmt->bindValue(2, $datos->titulo);
            $stmt->bindValue(3, $datos->descripcion);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->costo);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            }
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }

    public function Ver($idcurso, $idusuario)
    {
        try {
            $db = new DB();
            $conn = $db->connect();

            $sql = ("select id_curso, id_usuario_f, titulo, descripcion, 
            activo, imagen, costo from curso where id_curso = ? and id_usuario_f = ?;");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $idcurso);
            $stmt->bindValue(2, $idusuario);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array(
                    "id_curso" => $row['id_curso'],
                    "id_usuario_f" => $row['id_usuario_f'],
                    "titulo" => $row['titulo'],
                    "descripcion" => $row['descripcion'],
                    "activo" => $row['activo'],
                    "imagen" => base64_encode($row['imagen']),
                    "costo" => $row['costo']
                );
                return $msj;
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }

    function Listado($inicio, $registros)
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = ("SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso LIMIT $inicio,$registros");
        $stmt = $conn->prepare($sql);

        return $stmt;
    }
    public function TodosCursosMaestro()
    {
        $db = new DB();
        $conn = $db->connect();
        $sql = ("SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso");
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

        return $result;
    }
}

?>